<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class StatController extends Controller
{
    public function index(Request $request)
    {
        // Debug: Log request parameters
        Log::info('Statistics request parameters:', [
            'filter_type' => $request->input('filter_type'),
            'filter_value' => $request->input('filter_value'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'all_params' => $request->all()
        ]);

        $filterType = $request->input('filter_type');
        $filterValue = $request->input('filter_value');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $start = null;
        $end = null;

        // Xử lý lọc theo khoảng thời gian
        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();
        } elseif ($filterType === 'day' && $filterValue) {
            $start = Carbon::parse($filterValue)->startOfDay();
            $end = Carbon::parse($filterValue)->endOfDay();
        } elseif ($filterType === 'week' && $filterValue) {
            $start = Carbon::parse($filterValue)->startOfWeek();
            $end = Carbon::parse($filterValue)->endOfWeek();
        } elseif ($filterType === 'month' && $filterValue) {
            $start = Carbon::createFromFormat('Y-m', $filterValue)->startOfMonth();
            $end = Carbon::createFromFormat('Y-m', $filterValue)->endOfMonth();
        }

        // Lấy dữ liệu doanh thu theo loại filter
        $monthlyRevenue = collect();
        $paidRevenue = collect();
        
        if ($start && $end) {
            // Nếu có filter thời gian, lấy dữ liệu theo ngày
            $monthlyRevenue = DB::table('view_daily_revenue')
                ->whereBetween('date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->orderBy('date')
                ->get()
                ->map(function($item) {
                    return [
                        'date' => $item->date, // Sử dụng 'date' thay vì 'month' cho daily data
                        'revenue' => $item->revenue
                    ];
                });

            $paidRevenue = DB::table('view_daily_paid_revenue')
                ->whereBetween('date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->orderBy('date')
                ->get()
                ->map(function($item) {
                    return [
                        'date' => $item->date, // Sử dụng 'date' thay vì 'month' cho daily data
                        'paid_revenue' => $item->paid_revenue
                    ];
                });
        } else {
            // Nếu không có filter, lấy dữ liệu theo tháng (6 tháng gần nhất)
            $monthlyRevenue = DB::table('view_monthly_revenue')
                ->orderBy('month', 'desc')
                ->limit(6)
                ->get()
                ->reverse()
                ->values();

            $paidRevenue = DB::table('view_paid_revenue')
                ->orderBy('month', 'desc')
                ->limit(6)
                ->get()
                ->reverse()
                ->values();
        }

        $orderStatusCount = DB::table('view_order_status_count')
            ->when($start && $end, fn($q) => $q->whereBetween('created_at', [$start, $end]))
            ->get();

        $topProducts = DB::table('view_top_products')
            ->when($start && $end, fn($q) => $q->whereBetween('created_at', [$start, $end]))
            ->get();

        $topCoupons = DB::table('view_top_coupons')
            ->when($start && $end, fn($q) => $q->whereBetween('created_at', [$start, $end]))
            ->get();

        // Tính toán thống kê tổng quan theo khoảng thời gian
        $totalRevenue = 0;
        $totalOrders = 0;
        $totalUsers = 0;
        $totalProducts = 0;

        if ($start && $end) {
            // Doanh thu theo khoảng thời gian
            $totalRevenue = DB::table('orders')
                ->where('status', 'completed')
                ->whereBetween('created_at', [$start, $end])
                ->sum('total_amount');

            // Tổng đơn hàng theo khoảng thời gian
            $totalOrders = DB::table('orders')
                ->whereBetween('created_at', [$start, $end])
                ->count();

            // Người dùng mới theo khoảng thời gian
            $totalUsers = DB::table('users')
                ->whereBetween('created_at', [$start, $end])
                ->count();

            // Sản phẩm được tạo theo khoảng thời gian (chỉ sản phẩm đang hoạt động)
            $totalProducts = DB::table('products')
                ->where('status', 1) // Chỉ đếm sản phẩm đang hoạt động
                ->whereBetween('created_at', [$start, $end])
                ->count();
        } else {
            // Thống kê tổng quan không có filter
            $totalRevenue = DB::table('orders')->where('status', 'completed')->sum('total_amount');
            $totalOrders = DB::table('orders')->count();
            $totalUsers = DB::table('users')->where('status', 1)->count();
            $totalProducts = DB::table('products')->where('status', 1)->count();
        }

        return view('admin.stats.index', [
            'filterType' => $filterType,
            'filterValue' => $filterValue,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'start' => $start,
            'end' => $end,
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
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'totalUsers' => $totalUsers,
            'totalProducts' => $totalProducts,
        ]);
    }
}
