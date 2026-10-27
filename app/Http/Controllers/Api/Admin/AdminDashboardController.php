<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        $today = Carbon::today();
        $thirtyDaysAgo = Carbon::today()->subDays(29);

        // Revenue: orders that are PAID or DELIVERED
        $totalRevenue = (float) Order::whereIn('status', [Order::STATUS_PAID, Order::STATUS_DELIVERED])
            ->orWhere('payment_status', Order::PAYMENT_PAID)
            ->sum('total');

        $ordersToday = Order::whereDate('created_at', $today)->count();
        $revenueToday = (float) Order::whereDate('created_at', $today)
            ->whereNotIn('status', [Order::STATUS_CANCELLED])
            ->sum('total');

        $newUsers = User::whereDate('created_at', '>=', $thirtyDaysAgo)->count();
        $totalUsers = User::count();

        // Low stock count: stock < 10
        $lowStockCount = Product::where('stock', '<', 10)->count();

        // Last 30 days chart data
        $chartRaw = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as order_count'),
            DB::raw('SUM(CASE WHEN status != "CANCELLED" THEN total ELSE 0 END) as revenue')
        )
            ->whereDate('created_at', '>=', $thirtyDaysAgo)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $chartData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->format('Y-m-d');
            $row = $chartRaw->get($date);
            $chartData[] = [
                'date' => $date,
                'orders' => $row ? (int) $row->order_count : 0,
                'revenue' => $row ? round((float) $row->revenue, 2) : 0,
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Dashboard statistics retrieved.',
            'data' => [
                'total_revenue' => round($totalRevenue, 2),
                'revenue_today' => round($revenueToday, 2),
                'orders_today' => $ordersToday,
                'new_users' => $newUsers,
                'total_users' => $totalUsers,
                'low_stock_products' => $lowStockCount,
                'chart_last_30_days' => $chartData,
            ],
        ]);
    }
}
