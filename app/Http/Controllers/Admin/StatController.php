<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class StatController extends Controller
{
    public function index(Request $request)
    {
        $filterType = $request->input('filter_type');
        $filterValue = $request->input('filter_value');

        $monthlyRevenue = DB::table('view_monthly_revenue')
            ->when($filterType === 'month' && $filterValue, fn($q) => $q->where('month', $filterValue))
            ->get();

        $paidRevenue = DB::table('view_paid_revenue')
            ->when($filterType === 'month' && $filterValue, fn($q) => $q->where('month', $filterValue))
            ->get();

        return view('admin.stats.index', [
            'filterType' => $filterType,
            'filterValue' => $filterValue,
            'monthlyRevenue' => $monthlyRevenue,
            'paidRevenue' => $paidRevenue,
            'orderStatusCount' => DB::table('view_order_status_count')->get(),
            'topProducts' => DB::table('view_top_products')->get(),
            'categoryRevenue' => DB::table('view_revenue_by_category')->get(),
            'topCoupons' => DB::table('view_top_coupons')->get(),
            'mostViewedProducts' => DB::table('view_most_viewed_products')->get(),
            'stockByColor' => DB::table('view_stock_by_color')->get(),
            'userStatusCount' => DB::table('view_user_status_count')->get(),
            'averageProductRating' => DB::table('view_average_product_rating')->get(),
            'storageVariants' => DB::table('view_storage_variants')->get(),
            'totalStockPerProduct' => DB::table('view_total_stock_per_product')->get(),
        ]);
    }
}