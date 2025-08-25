<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;

class CheckDeliveredOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:check-delivered {--days=1 : Số ngày để kiểm tra}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kiểm tra và thống kê các đơn hàng đã giao và đã hoàn thành';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $this->info("🔍 Kiểm tra đơn hàng đã giao và đã hoàn thành trong {$days} ngày qua...");

        // Tổng số đơn hàng delivered
        $totalDelivered = Order::where('status', 'delivered')->count();
        $this->info("📦 Tổng số đơn hàng đã giao (chưa hoàn thành): {$totalDelivered}");

        // Đơn hàng đã được xác nhận nhận (hoàn thành)
        $totalCompleted = Order::where('status', 'completed')->count();
        $this->info("✅ Đơn hàng đã hoàn thành: {$totalCompleted}");

        // Đơn hàng chưa được xác nhận nhận
        $totalNotReceived = Order::where('status', 'delivered')->where('is_received', false)->count();
        $this->info("⏳ Đơn hàng chưa được xác nhận nhận (sẽ tự động hoàn thành): {$totalNotReceived}");

        // Đơn hàng sẽ được tự động chuyển trạng thái
        $ordersToAutoConfirm = Order::where('status', 'delivered')
            ->where('is_received', false)
            ->where('updated_at', '<=', Carbon::now()->subDays($days))
            ->get();

        $this->newLine();
        $this->info("🔄 Đơn hàng sẽ được tự động hoàn thành (sau {$days} ngày): {$ordersToAutoConfirm->count()}");

        if ($ordersToAutoConfirm->isNotEmpty()) {
            $this->table(
                ['ID', 'Mã đơn hàng', 'Khách hàng', 'Cập nhật lúc', 'Đã qua'],
                $ordersToAutoConfirm->map(function ($order) {
                    return [
                        $order->id,
                        $order->code_order ?? '#' . $order->id,
                        $order->user ? $order->user->name : 'Khách vãng lai',
                        $order->updated_at->format('d/m/Y H:i:s'),
                        $order->updated_at->diffForHumans()
                    ];
                })->toArray()
            );
        }

        // Thống kê theo ngày
        $this->newLine();
        $this->info('📊 Thống kê theo ngày:');

        for ($i = 1; $i <= 7; $i++) {
            $count = Order::where('status', 'delivered')
                ->where('is_received', false)
                ->where('updated_at', '<=', Carbon::now()->subDays($i))
                ->count();

            if ($count > 0) {
                $this->info("   - Sau {$i} ngày: {$count} đơn hàng");
            }
        }

        // Đơn hàng mới được giao hôm nay
        $todayDelivered = Order::where('status', 'delivered')
            ->whereDate('updated_at', Carbon::today())
            ->count();

        $this->newLine();
        $this->info("📅 Đơn hàng được giao hôm nay: {$todayDelivered}");

        // Đơn hàng được giao trong tuần này
        $thisWeekDelivered = Order::where('status', 'delivered')
            ->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->count();

        $this->info("📅 Đơn hàng được giao trong tuần này: {$thisWeekDelivered}");

        return 0;
    }
}
