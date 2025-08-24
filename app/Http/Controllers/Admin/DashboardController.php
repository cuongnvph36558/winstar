<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Debug: Log request parameters
        Log::info('Dashboard request parameters:', [
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'all_params' => $request->all()
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $start = null;
        $end = null;

        // Xử lý lọc theo khoảng thời gian
        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();
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
                        'date' => $item->date,
                        'revenue' => $item->revenue
                    ];
                });

            $paidRevenue = DB::table('view_daily_paid_revenue')
                ->whereBetween('date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                ->orderBy('date')
                ->get()
                ->map(function($item) {
                    return [
                        'date' => $item->date,
                        'paid_revenue' => $item->paid_revenue
                    ];
                });
        } else {
            // Nếu không có filter, lấy dữ liệu theo ngày (30 ngày gần nhất)
            $startDate = Carbon::now()->subDays(30)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
            
            $monthlyRevenue = DB::table('view_daily_revenue')
                ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->orderBy('date')
                ->get()
                ->map(function($item) {
                    return [
                        'date' => $item->date,
                        'revenue' => $item->revenue
                    ];
                });

            $paidRevenue = DB::table('view_daily_paid_revenue')
                ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->orderBy('date')
                ->get()
                ->map(function($item) {
                    return [
                        'date' => $item->date,
                        'paid_revenue' => $item->paid_revenue
                    ];
                });
        }

        // Thống kê đơn hàng theo trạng thái
        if ($start && $end) {
            $orderStatusCount = DB::table('orders')
                ->select('status', DB::raw('count(*) as count'))
                ->whereBetween('created_at', [$start, $end])
                ->groupBy('status')
                ->get();
        } else {
            $orderStatusCount = DB::table('view_order_status_count')->get();
        }

        // Top sản phẩm bán chạy
        if ($start && $end) {
            $topProducts = DB::table('order_details as od')
                ->join('orders as o', 'od.order_id', '=', 'o.id')
                ->join('products as p', 'od.product_id', '=', 'p.id')
                ->select(
                    'p.id',
                    'p.name',
                    DB::raw('COALESCE(CONCAT(p.name, " ", c.name, " ", s.capacity), p.name) AS variant_name'),
                    DB::raw('SUM(od.quantity) AS total_sold')
                )
                ->leftJoin('product_variants as pv', 'od.variant_id', '=', 'pv.id')
                ->leftJoin('colors as c', 'pv.color_id', '=', 'c.id')
                ->leftJoin('storages as s', 'pv.storage_id', '=', 's.id')
                ->where('p.status', 1)
                ->whereBetween('o.created_at', [$start, $end])
                ->where('o.status', 'completed')
                ->groupBy('p.id', 'p.name', 'c.name', 's.capacity')
                ->orderByDesc('total_sold')
                ->get();
        } else {
            $topProducts = DB::table('view_top_products')->get();
        }

        // Top mã giảm giá
        if ($start && $end) {
            $topCoupons = DB::table('coupons as c')
                ->join('orders as o', 'o.coupon_id', '=', 'c.id')
                ->select('c.id', 'c.code', DB::raw('COUNT(o.id) AS total_usage'))
                ->whereBetween('o.created_at', [$start, $end])
                ->groupBy('c.id', 'c.code')
                ->orderByDesc('total_usage')
                ->get();
        } else {
            $topCoupons = DB::table('view_top_coupons')->get();
        }

        // Top khách hàng
        if ($start && $end) {
            $topCustomers = DB::table('users as u')
                ->join('orders as o', 'u.id', '=', 'o.user_id')
                ->leftJoin('points as p', 'u.id', '=', 'p.user_id')
                ->select(
                    'u.id',
                    'u.name',
                    'u.email',
                    'u.phone',
                    DB::raw('COUNT(o.id) AS total_orders'),
                    DB::raw('SUM(o.total_amount) AS total_spent'),
                    DB::raw('AVG(o.total_amount) AS avg_order_value'),
                    DB::raw('MIN(o.created_at) as first_order_date'),
                    DB::raw('MAX(o.created_at) as last_order_date'),
                    DB::raw('COALESCE(p.earned_points, 0) AS total_earned_points'),
                    DB::raw('CASE 
                        WHEN COALESCE(p.earned_points, 0) >= 600000 THEN "Diamond"
                        WHEN COALESCE(p.earned_points, 0) >= 390000 THEN "Platinum"
                        WHEN COALESCE(p.earned_points, 0) >= 330000 THEN "Gold"
                        WHEN COALESCE(p.earned_points, 0) >= 240000 THEN "Silver"
                        ELSE "Bronze"
                    END AS vip_level'),


                )
                ->where('o.status', 'completed')
                ->whereBetween('o.created_at', [$start, $end])
                ->groupBy('u.id', 'u.name', 'u.email', 'u.phone', 'p.earned_points')
                ->orderByDesc('total_spent')
                ->orderByDesc('total_orders')
                ->limit(10)
                ->get();
        } else {
            $topCustomers = DB::table('view_top_customers')->limit(10)->get();
        }

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
                ->where('status', 1)
                ->whereBetween('created_at', [$start, $end])
                ->count();
        } else {
            // Thống kê tổng quan không có filter
            $totalRevenue = DB::table('orders')->where('status', 'completed')->sum('total_amount');
            $totalOrders = DB::table('orders')->count();
            $totalUsers = DB::table('users')->where('status', 1)->count();
            $totalProducts = DB::table('products')->where('status', 1)->count();
        }

        // Doanh thu theo danh mục
        if ($start && $end) {
            $categoryRevenue = DB::table('order_details as od')
                ->join('orders as o', 'od.order_id', '=', 'o.id')
                ->join('products as p', 'od.product_id', '=', 'p.id')
                ->join('categories as cat', 'p.category_id', '=', 'cat.id')
                ->select('cat.id AS category_id', 'cat.name AS category_name', DB::raw('SUM(od.total) AS revenue'))
                ->where('p.status', 1)
                ->where('o.status', 'completed')
                ->whereBetween('o.created_at', [$start, $end])
                ->groupBy('cat.id', 'cat.name')
                ->orderByDesc('revenue')
                ->get();
        } else {
            $categoryRevenue = DB::table('view_revenue_by_category')->get();
        }

        return view('admin.dashboard', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'start' => $start,
            'end' => $end,
            'monthlyRevenue' => $monthlyRevenue,
            'paidRevenue' => $paidRevenue,
            'orderStatusCount' => $orderStatusCount,
            'topProducts' => $topProducts,
            'categoryRevenue' => $categoryRevenue,
            'topCoupons' => $topCoupons,
            'topCustomers' => $topCustomers,
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