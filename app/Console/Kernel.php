<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        
        // Tự động chuyển trạng thái đơn hàng từ "delivered" sang "received" sau 1 ngày
        // Chạy mỗi giờ để kiểm tra các đơn hàng cần chuyển trạng thái
        $schedule->command('orders:auto-confirm-delivered')
            ->hourly()
            ->withoutOverlapping()
            ->runInBackground()
            ->appendOutputTo(storage_path('logs/auto-confirm-orders.log'));
            
        // Tự động sửa trạng thái thanh toán cho đơn hàng đã giao
        // Chạy mỗi 30 phút để kiểm tra và sửa các đơn hàng có trạng thái thanh toán chưa đúng
        $schedule->command('orders:fix-payment-status')
            ->everyThirtyMinutes()
            ->withoutOverlapping()
            ->runInBackground()
            ->appendOutputTo(storage_path('logs/fix-payment-status.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
