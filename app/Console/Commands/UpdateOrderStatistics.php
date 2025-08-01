<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Services\PointService;

class UpdateOrderStatistics extends Command
{
    protected $signature = 'orders:update-stats';
    protected $description = 'Cập nhật thống kê đơn hàng và xử lý điểm tích lũy';

    protected $pointService;

    public function __construct(PointService $pointService)
    {
        parent::__construct();
        $this->pointService = $pointService;
    }

    public function handle()
    {
        $this->info('Bắt đầu cập nhật thống kê đơn hàng...');

        try {
            // 1. Cập nhật điểm tích lũy cho các đơn hàng đã hoàn thành
            $this->updateCompletedOrdersPoints();

            // 2. Refresh các view thống kê
            $this->refreshStatisticsViews();

            // 3. Xử lý điểm hết hạn
            $this->pointService->processExpiredPoints();

            $this->info('Cập nhật thống kê hoàn thành!');
            return 0;
        } catch (\Exception $e) {
            $this->error('Lỗi: ' . $e->getMessage());
            return 1;
        }
    }

    private function updateCompletedOrdersPoints()
    {
        $completedOrders = Order::where('status', 'completed')
            ->whereNull('points_earned')
            ->get();

        $count = 0;
        foreach ($completedOrders as $order) {
            try {
                $result = $this->pointService->earnPointsFromOrder($order->user, $order);
                if ($result) {
                    $count++;
                }
            } catch (\Exception $e) {
                $this->warn("Lỗi xử lý điểm cho đơn hàng #{$order->code_order}: " . $e->getMessage());
            }
        }

        $this->info("Đã xử lý điểm tích lũy cho {$count} đơn hàng.");
    }

    private function refreshStatisticsViews()
    {
        // MySQL không hỗ trợ REFRESH TABLE, thay vào đó chúng ta sẽ tạo lại các view
        try {
            // Tạo lại view_monthly_revenue
            DB::statement("CREATE OR REPLACE VIEW view_monthly_revenue AS
                SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, SUM(total_amount) AS revenue
                FROM orders
                WHERE status = 'completed'
                GROUP BY month
            ");
            $this->line("Đã refresh view: view_monthly_revenue");

            // Tạo lại view_paid_revenue
            DB::statement("CREATE OR REPLACE VIEW view_paid_revenue AS
                SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, SUM(total_amount) AS paid_revenue
                FROM orders
                WHERE payment_status = 'paid'
                GROUP BY month
            ");
            $this->line("Đã refresh view: view_paid_revenue");

            // Tạo lại view_order_status_count
            DB::statement("CREATE OR REPLACE VIEW view_order_status_count AS
                SELECT status, COUNT(*) AS count, MIN(created_at) as created_at
                FROM orders
                GROUP BY status
            ");
            $this->line("Đã refresh view: view_order_status_count");

            // Tạo lại view_top_products
            DB::statement("CREATE OR REPLACE VIEW view_top_products AS
                SELECT p.id, p.name, SUM(od.quantity) AS total_sold, MIN(od.created_at) as created_at
                FROM order_details od
                JOIN products p ON od.product_id = p.id
                GROUP BY p.id, p.name
                ORDER BY total_sold DESC
            ");
            $this->line("Đã refresh view: view_top_products");

            // Tạo lại view_top_coupons
            DB::statement("CREATE OR REPLACE VIEW view_top_coupons AS
                SELECT c.id, c.code, COUNT(o.id) AS used_count, MIN(o.created_at) as created_at
                FROM coupons c
                JOIN orders o ON o.coupon_id = c.id
                GROUP BY c.id, c.code
                ORDER BY used_count DESC
            ");
            $this->line("Đã refresh view: view_top_coupons");

        } catch (\Exception $e) {
            $this->warn("Lỗi refresh views: " . $e->getMessage());
        }
    }
}
