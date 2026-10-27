<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\OrderConfirmationMail;
use App\Mail\OrderStatusUpdatedMail;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $orders = Order::with('items')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Orders retrieved successfully.',
            'data' => $orders->items(),
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'total' => $orders->total(),
            ],
        ]);
    }

    public function show(Request $request, string $orderNumber): JsonResponse
    {
        $order = Order::with(['items', 'coupon:id,code,type,value'])
            ->where('user_id', $request->user()->id)
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'message' => 'Order details retrieved.',
            'data' => $order,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'address_id' => ['nullable', 'exists:addresses,id'],
            'address' => ['nullable', 'array'],
            'address.full_name' => ['required_with:address', 'string'],
            'address.phone' => ['required_with:address', 'string'],
            'address.line1' => ['required_with:address', 'string'],
            'address.line2' => ['nullable', 'string'],
            'address.city' => ['required_with:address', 'string'],
            'address.state' => ['required_with:address', 'string'],
            'address.pincode' => ['required_with:address', 'string'],
            'coupon_code' => ['nullable', 'string'],
            'payment_method' => ['nullable', 'in:cod,razorpay'],
            'notes' => ['nullable', 'string', 'max:500'],
            'items' => ['nullable', 'array'],
            'items.*.product_id' => ['required_with:items', 'exists:products,id'],
            'items.*.quantity' => ['required_with:items', 'integer', 'min:1'],
        ]);

        $user = $request->user();

        // 1. Resolve Address Snapshot
        $addressSnapshot = [];
        if (!empty($validated['address_id'])) {
            $addr = Address::where('user_id', $user->id)->where('id', $validated['address_id'])->firstOrFail();
            $addressSnapshot = [
                'full_name' => $addr->full_name,
                'phone' => $addr->phone,
                'line1' => $addr->line1,
                'line2' => $addr->line2,
                'city' => $addr->city,
                'state' => $addr->state,
                'pincode' => $addr->pincode,
            ];
        } elseif (!empty($validated['address'])) {
            $addressSnapshot = $validated['address'];
        } else {
            // Pick default address
            $addr = Address::where('user_id', $user->id)->where('is_default', true)->first();
            if (!$addr) {
                $addr = Address::where('user_id', $user->id)->latest()->first();
            }
            if (!$addr) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please provide a delivery address.',
                    'data' => null,
                ], 422);
            }
            $addressSnapshot = [
                'full_name' => $addr->full_name,
                'phone' => $addr->phone,
                'line1' => $addr->line1,
                'line2' => $addr->line2,
                'city' => $addr->city,
                'state' => $addr->state,
                'pincode' => $addr->pincode,
            ];
        }

        // 2. Resolve Items (either from request or user's active cart)
        $orderItemsPayload = [];
        if (!empty($validated['items'])) {
            $orderItemsPayload = $validated['items'];
        } else {
            $cart = Cart::with('items')->where('user_id', $user->id)->first();
            if (!$cart || $cart->items->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your cart is empty.',
                    'data' => null,
                ], 422);
            }
            foreach ($cart->items as $ci) {
                $orderItemsPayload[] = [
                    'product_id' => $ci->product_id,
                    'quantity' => $ci->quantity,
                ];
            }
        }

        // 3. Execute Transaction with lockForUpdate on product stock
        $order = DB::transaction(function () use ($user, $validated, $addressSnapshot, $orderItemsPayload) {
            $productIds = collect($orderItemsPayload)->pluck('product_id')->unique()->toArray();

            // Lock products for update to prevent race conditions & overselling
            $products = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

            $subtotal = 0;
            $itemsToCreate = [];

            foreach ($orderItemsPayload as $itemData) {
                $product = $products->get($itemData['product_id']);

                if (!$product || !$product->is_active) {
                    throw ValidationException::withMessages([
                        'items' => ["Product '{$product?->name}' is no longer available."],
                    ]);
                }

                if ($product->stock < $itemData['quantity']) {
                    throw ValidationException::withMessages([
                        'items' => [
                            "Insufficient stock for '{$product->name}'. Available: {$product->stock}, Requested: {$itemData['quantity']}.",
                        ],
                    ]);
                }

                // Decrement stock
                $product->decrement('stock', $itemData['quantity']);

                $itemSubtotal = round((float) $product->price * $itemData['quantity'], 2);
                $subtotal += $itemSubtotal;

                $itemsToCreate[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'price' => (float) $product->price,
                    'mrp' => (float) $product->mrp,
                    'quantity' => $itemData['quantity'],
                    'subtotal' => $itemSubtotal,
                ];
            }

            // Coupon calculation
            $discount = 0;
            $couponId = null;
            if (!empty($validated['coupon_code'])) {
                $coupon = Coupon::where('code', strtoupper(trim($validated['coupon_code'])))->first();
                if ($coupon) {
                    $check = $coupon->isValidFor($subtotal, $user);
                    if ($check['valid']) {
                        $discount = $coupon->calculateDiscount($subtotal);
                        $couponId = $coupon->id;
                        $coupon->increment('usage_count');
                    }
                }
            }

            // Shipping and GST settings
            $shippingCharge = (float) Setting::get('shipping_charge', 99.00);
            $freeThreshold = (float) Setting::get('free_shipping_threshold', 999.00);
            $shipping = ($subtotal >= $freeThreshold) ? 0.00 : $shippingCharge;

            $gstRate = (float) Setting::get('gst_rate', 18.00);
            $taxableSubtotal = max(0, $subtotal - $discount);
            $gstAmount = round(($taxableSubtotal * $gstRate) / (100 + $gstRate), 2); // GST included in price

            $total = round($taxableSubtotal + $shipping, 2);

            $orderNumber = 'FX-' . date('Ymd') . '-' . strtoupper(Str::random(5));
            $paymentMethod = $validated['payment_method'] ?? Order::METHOD_COD;

            $newOrder = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $user->id,
                'coupon_id' => $couponId,
                'status' => Order::STATUS_PENDING,
                'payment_status' => Order::PAYMENT_PENDING,
                'payment_method' => $paymentMethod,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'shipping' => $shipping,
                'gst_amount' => $gstAmount,
                'total' => $total,
                'address_snapshot' => $addressSnapshot, // Encrypted by model cast
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($itemsToCreate as $item) {
                $newOrder->items()->create($item);
            }

            // Clear User's Cart
            $cart = Cart::where('user_id', $user->id)->first();
            $cart?->items()->delete();

            return $newOrder;
        });

        // Dispatch Order Confirmation Email
        try {
            Mail::to($user->email)->queue(new OrderConfirmationMail($order->load('items')));
        } catch (\Throwable $e) {
            // Dev environment fallback
        }

        return response()->json([
            'success' => true,
            'message' => 'Order placed successfully!',
            'data' => $order->load('items'),
        ], 201);
    }

    public function cancel(Request $request, string $orderNumber): JsonResponse
    {
        $user = $request->user();
        $order = Order::with('items.product')
            ->where('user_id', $user->id)
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        if (!$order->canBeCancelled()) {
            return response()->json([
                'success' => false,
                'message' => "Order cannot be cancelled in its current status ({$order->status}).",
                'data' => null,
            ], 422);
        }

        DB::transaction(function () use ($order) {
            // Restore inventory
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }

            $previousStatus = $order->status;
            $order->update([
                'status' => Order::STATUS_CANCELLED,
            ]);

            // Dispatch cancellation update email
            try {
                Mail::to($order->user->email)->queue(new OrderStatusUpdatedMail($order, $previousStatus));
            } catch (\Throwable $e) {
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Order cancelled successfully and stock restored.',
            'data' => $order->fresh(),
        ]);
    }

    public function invoice(Request $request, string $orderNumber): Response|JsonResponse
    {
        $order = Order::with(['items', 'user'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        // Ensure user owns order or is admin
        if ($order->user_id !== $request->user()->id && !$request->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
                'data' => null,
            ], 403);
        }

        $pdf = Pdf::loadView('pdf.invoice', compact('order'));
        return $pdf->download("invoice-{$order->order_number}.pdf");
    }
}
