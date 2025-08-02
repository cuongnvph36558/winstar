<?php

namespace App\Listeners;

use App\Events\OrderStatusUpdated;
use App\Services\PointService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class HandleOrderStatusUpdate implements ShouldQueue
{
    use InteractsWithQueue;

    protected $pointService;

    public function __construct(PointService $pointService)
    {
        $this->pointService = $pointService;
    }

    public function handle(OrderStatusUpdated $event)
    {
        $order = $event->order;
        $oldStatus = $event->oldStatus;
        $newStatus = $event->newStatus;

        try {
            // Xử lý điểm tích lũy khi đơn hàng hoàn thành
            if ($newStatus === 'completed' && $oldStatus !== 'completed') {
                $this->pointService->earnPointsFromOrder($order->user, $order);
                Log::info("Đã tích điểm cho đơn hàng #{$order->code_order}");
            }

            // Xử lý khi đơn hàng bị hủy - hoàn trả kho
            if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                $this->restoreStock($order);
                Log::info("Đã hoàn trả kho cho đơn hàng #{$order->code_order}");
            }

            // Cập nhật thống kê
            $this->updateStatistics();

        } catch (\Exception $e) {
            Log::error("Lỗi xử lý cập nhật trạng thái đơn hàng #{$order->code_order}: " . $e->getMessage());
        }
    }

    private function restoreStock($order)
    {
        foreach ($order->orderDetails as $detail) {
            if ($detail->variant) {
                $detail->variant->increment('stock_quantity', $detail->quantity);
            } else {
                $detail->product->increment('stock_quantity', $detail->quantity);
            }
        }
    }

    private function updateStatistics()
    {
        // Có thể gọi command để cập nhật thống kê
        // \Artisan::call('orders:update-stats');
    }
}
