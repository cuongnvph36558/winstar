<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class StatController extends Controller
{
    public function index(Request $request)
    {
        $filterType = $request->input('filter_type');
        $filterValue = $request->input('filter_value');

        $start = null;
        $end = null;

        if ($filterType === 'day' && $filterValue) {
            $start = Carbon::parse($filterValue)->startOfDay();
            $end = Carbon::parse($filterValue)->endOfDay();
        } elseif ($filterType === 'week' && $filterValue) {
            $start = Carbon::parse($filterValue)->startOfWeek();
            $end = Carbon::parse($filterValue)->endOfWeek();
        } elseif ($filterType === 'month' && $filterValue) {
            $start = Carbon::createFromFormat('Y-m', $filterValue)->startOfMonth();
            $end = Carbon::createFromFormat('Y-m', $filterValue)->endOfMonth();
        }

        $monthlyRevenue = DB::table('view_monthly_revenue')
            ->when($filterType === 'month' && $filterValue, fn($q) => $q->where('month', $filterValue))
            ->get();

        $paidRevenue = DB::table('view_paid_revenue')
            ->when($filterType === 'month' && $filterValue, fn($q) => $q->where('month', $filterValue))
            ->get();

        $orderStatusCount = DB::table('view_order_status_count')
            ->when($start && $end, fn($q) => $q->whereBetween('created_at', [$start, $end]))
            ->get();

        $topProducts = DB::table('view_top_products')
            ->when($start && $end, fn($q) => $q->whereBetween('created_at', [$start, $end]))
            ->get();

        $topCoupons = DB::table('view_top_coupons')
            ->when($start && $end, fn($q) => $q->whereBetween('created_at', [$start, $end]))
            ->get();

        return view('admin.stats.index', [
            'filterType' => $filterType,
            'filterValue' => $filterValue,
            'monthlyRevenue' => $monthlyRevenue,
            'paidRevenue' => $paidRevenue,
            'orderStatusCount' => $orderStatusCount,
            'topProducts' => $topProducts,
            'categoryRevenue' => DB::table('view_revenue_by_category')->get(),
            'topCoupons' => $topCoupons,
            'mostViewedProducts' => DB::table('view_most_viewed_products')->get(),
            'stockByColor' => DB::table('view_stock_by_color')->get(),
            'userStatusCount' => DB::table('view_user_status_count')->get(),
            'averageProductRating' => DB::table('view_average_product_rating')->get(),
            'storageVariants' => DB::table('view_storage_variants')->get(),
            'totalStockPerProduct' => DB::table('view_total_stock_per_product')->get(),
        ]);
    }
}
