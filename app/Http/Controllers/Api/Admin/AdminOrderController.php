<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderStatusUpdatedMail;
use App\Models\AuditLog;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminOrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Order::with(['user:id,name,email', 'items']);

        if ($q = $request->input('q')) {
            $query->where(function ($sub) use ($q) {
                $sub->where('order_number', 'like', "%{$q}%")
                    ->orWhere('tracking_number', 'like', "%{$q}%")
                    ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%"));
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($paymentStatus = $request->input('payment_status')) {
            $query->where('payment_status', $paymentStatus);
        }

        if ($from = $request->input('from_date')) {
            $query->whereDate('created_at', '>=', Carbon::parse($from));
        }
        if ($to = $request->input('to_date')) {
            $query->whereDate('created_at', '<=', Carbon::parse($to));
        }

        $orders = $query->latest()->paginate((int) $request->input('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Admin orders retrieved.',
            'data' => $orders->items(),
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'total' => $orders->total(),
            ],
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $order = Order::with(['user', 'items.product', 'coupon'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Order details retrieved.',
            'data' => $order,
        ]);
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:PENDING,PAID,PACKED,SHIPPED,DELIVERED,CANCELLED,REFUNDED'],
            'tracking_number' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $order = Order::with('user')->findOrFail($id);
        $previousStatus = $order->status;
        $old = [
            'status' => $order->status,
            'tracking_number' => $order->tracking_number,
        ];

        $updateData = ['status' => $validated['status']];

        if (array_key_exists('tracking_number', $validated)) {
            $updateData['tracking_number'] = $validated['tracking_number'];
        }
        if (array_key_exists('notes', $validated)) {
            $updateData['notes'] = $validated['notes'];
        }

        // If status moved to PAID, update payment_status
        if ($validated['status'] === Order::STATUS_PAID && $order->payment_status !== Order::PAYMENT_PAID) {
            $updateData['payment_status'] = Order::PAYMENT_PAID;
        }

        $order->update($updateData);

        AuditLog::log('order.status_updated', $order, $old, [
            'status' => $order->status,
            'tracking_number' => $order->tracking_number,
        ]);

        // Dispatch queued status update email to customer
        try {
            Mail::to($order->user->email)->queue(new OrderStatusUpdatedMail($order, $previousStatus));
        } catch (\Throwable $e) {
        }

        return response()->json([
            'success' => true,
            'message' => "Order status updated to {$order->status}.",
            'data' => $order->fresh(),
        ]);
    }

    public function markRefunded(Request $request, int $id): JsonResponse
    {
        $order = Order::with('items.product')->findOrFail($id);
        $old = [
            'status' => $order->status,
            'payment_status' => $order->payment_status,
        ];

        $order->update([
            'status' => Order::STATUS_REFUNDED,
            'payment_status' => Order::PAYMENT_REFUNDED,
        ]);

        // Restock inventory if previously packed or paid
        if ($request->boolean('restock', true)) {
            foreach ($order->items as $item) {
                $item->product?->increment('stock', $item->quantity);
            }
        }

        AuditLog::log('order.refunded', $order, $old, [
            'status' => Order::STATUS_REFUNDED,
            'payment_status' => Order::PAYMENT_REFUNDED,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order marked as refunded successfully.',
            'data' => $order->fresh(),
        ]);
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="foxal-orders-' . date('Y-m-d') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Order Number',
                'Customer Name',
                'Customer Email',
                'Status',
                'Payment Status',
                'Payment Method',
                'Subtotal',
                'Discount',
                'Shipping',
                'Total',
                'Tracking Number',
                'Order Date',
            ]);

            Order::with('user')->chunk(200, function ($orders) use ($handle) {
                foreach ($orders as $o) {
                    fputcsv($handle, [
                        $o->order_number,
                        $o->user?->name ?? 'Guest',
                        $o->user?->email ?? 'N/A',
                        $o->status,
                        $o->payment_status,
                        $o->payment_method,
                        $o->subtotal,
                        $o->discount,
                        $o->shipping,
                        $o->total,
                        $o->tracking_number ?? 'N/A',
                        $o->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }
}
