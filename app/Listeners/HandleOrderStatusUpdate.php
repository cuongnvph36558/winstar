<?php

namespace App\Listeners;

use App\Events\OrderStatusUpdated;
use App\Services\PointService;
use App\Notifications\OrderNotification;
use App\Models\User;
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
                
                // Gửi thông báo cho admin khi khách hàng xác nhận đã nhận hàng
                // Chỉ gửi khi không phải là action confirm_received (để tránh trùng lặp)
                $actionDetails = $event->actionDetails ?? [];
                $isConfirmReceived = isset($actionDetails['action']) && $actionDetails['action'] === 'confirm_received';
                
                if ($oldStatus === 'shipping' && !$isConfirmReceived) {
                    $adminUsers = User::whereHas('roles', function($query) {
                        $query->where('name', 'admin');
                    })->get();
                    
                    foreach ($adminUsers as $admin) {
                        $admin->notify(new OrderNotification($order, 'customer_confirmed'));
                    }
                    
                    Log::info("Đã gửi thông báo cho admin về việc khách hàng xác nhận đã nhận hàng #{$order->code_order}");
                }
            }

        } catch (\Exception $e) {
            Log::error("Lỗi xử lý cập nhật trạng thái đơn hàng #{$order->code_order}: " . $e->getMessage());
        }
    }
} 