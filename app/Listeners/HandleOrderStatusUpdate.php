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

        } catch (\Exception $e) {
            Log::error("Lỗi xử lý cập nhật trạng thái đơn hàng #{$order->code_order}: " . $e->getMessage());
        }
    }
} 