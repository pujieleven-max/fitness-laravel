<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StockMutation;
use App\Models\Supplement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->startOfMonth()->startOfDay();
        $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();

        // Orders within range
        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->get();

        $totalRevenue = $orders->sum('total_amount');
        $totalOrdersCount = $orders->count();

        // Breakdown by payment method
        $paymentMethods = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->select('payment_method', DB::raw('COUNT(*) as total_count'), DB::raw('SUM(total_amount) as total_sum'))
            ->groupBy('payment_method')
            ->get();

        // Breakdown by item type (supplement, gym_package, booking)
        $itemTypesBreakdown = OrderItem::whereHas('order', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])->where('status', 'completed');
            })
            ->select('item_type', DB::raw('SUM(subtotal) as revenue'), DB::raw('SUM(quantity) as qty'), DB::raw('SUM((unit_price - cost_price) * quantity) as profit'))
            ->groupBy('item_type')
            ->get();

        $totalProfit = $itemTypesBreakdown->sum('profit');

        // Total inventory valuation
        $totalStockValue = Supplement::where('is_active', true)
            ->select(DB::raw('SUM(stock * purchase_price) as stock_cost'), DB::raw('SUM(stock * selling_price) as stock_revenue_potential'))
            ->first();

        // Top selling products in date range
        $topProducts = OrderItem::whereHas('order', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])->where('status', 'completed');
            })
            ->select('name', 'item_type', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_sales'), DB::raw('SUM((unit_price - cost_price) * quantity) as product_profit'))
            ->groupBy('name', 'item_type')
            ->orderByDesc('total_sales')
            ->take(10)
            ->get();

        return view('reports.index', compact(
            'startDate',
            'endDate',
            'totalRevenue',
            'totalOrdersCount',
            'totalProfit',
            'paymentMethods',
            'itemTypesBreakdown',
            'totalStockValue',
            'topProducts'
        ));
    }
}
