<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class StatController extends Controller
{
    public function index(Request $request)
    {
        // Lấy loại lọc (ngày / tuần / tháng) và giá trị lọc từ request
        $filterType = $request->input('filter_type');
        $filterValue = $request->input('filter_value');

        // Lấy doanh thu theo tháng từ view SQL, có thể lọc theo tháng được chọn nếu có
        $monthlyRevenue = DB::table('view_monthly_revenue')
            ->when($filterType === 'month' && $filterValue, fn($q) => $q->where('month', $filterValue))
            ->get();

        // Tổng doanh thu đã thanh toán, cũng lọc theo tháng nếu được chọn
        $paidRevenue = DB::table('view_paid_revenue')
            ->when($filterType === 'month' && $filterValue, fn($q) => $q->where('month', $filterValue))
            ->get();

        // Trả dữ liệu về view với đầy đủ các loại thống kê
        return view('admin.stats.index', [
            // Truyền lại thông tin lọc để hiển thị lại đúng form
            'filterType' => $filterType,
            'filterValue' => $filterValue,

            // Dữ liệu thống kê chính
            'monthlyRevenue' => $monthlyRevenue,
            'paidRevenue' => $paidRevenue,

            // Dữ liệu thống kê phụ không lọc theo thời gian (có thể cập nhật sau)
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