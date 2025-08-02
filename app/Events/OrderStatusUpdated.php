<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OrderStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $oldStatus;
    public $newStatus;

    public function __construct(Order $order, $oldStatus, $newStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        
        // Debug log
        Log::info('OrderStatusUpdated event created', [
            'order_id' => $order->id,
            'order_code' => $order->code_order,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'user_id' => $order->user_id
        ]);
    }

    public function broadcastOn()
    {
        return [
            // Public channel for general order updates
            new Channel('orders'),
            // Admin channel for admin notifications
            new Channel('admin.orders'),
            // User-specific private channel for personal notifications
            new PrivateChannel('user.' . $this->order->user_id),
        ];
    }

    public function broadcastAs()
    {
        return 'OrderStatusUpdated';
    }

    public function broadcastWith()
    {
        $data = [
            'order_id' => $this->order->id,
            'order_code' => $this->order->code_order,
            'user_id' => $this->order->user_id,
            'user_name' => $this->order->user->name ?? 'Unknown',
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'updated_at' => $this->order->updated_at->toISOString(),
            'message' => $this->getStatusMessage()
        ];
        
        Log::info('OrderStatusUpdated broadcasting data', $data);
        
        return $data;
    }
    
    private function getStatusMessage()
    {
        $statusMessages = [
            'pending' => 'Đơn hàng đang chờ xử lý',
            'processing' => 'Đơn hàng đang được chuẩn bị',
            'shipping' => 'Đơn hàng đang được giao',
            'completed' => 'Đơn hàng đã hoàn thành',
            'cancelled' => 'Đơn hàng đã bị hủy',
        ];
        
        return $statusMessages[$this->newStatus] ?? "Đơn hàng {$this->order->code_order} đã được cập nhật";
    }
}
