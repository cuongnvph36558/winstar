<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $oldStatus;
    public $newStatus;

    public function __construct(Order $order, $oldStatus = null, $newStatus = null)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus ?? $order->status;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('orders'),
            new PrivateChannel('user.' . $this->order->user_id),
            new Channel('admin.orders')
        ];
    }

    public function broadcastAs(): string
    {
        return 'OrderStatusUpdated';
    }

    public function broadcastWith(): array
    {
        return [
            'order_id' => $this->order->id,
            'order_code' => $this->order->order_code,
            'user_id' => $this->order->user_id,
            'user_name' => $this->order->user->name ?? 'Unknown',
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'status_text' => $this->getStatusText($this->newStatus),
            'total_amount' => $this->order->total_amount,
            'updated_at' => optional($this->order->updated_at)->toISOString(),
            'message' => $this->getNotificationMessage(),
        ];
    }

    private function getStatusText($status): string
    {
        return [
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang chuẩn bị hàng',
            'shipping' => 'Đang giao hàng',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
        ][$status] ?? $status;
    }

    private function getNotificationMessage(): string
    {
        return [
            'pending' => 'Đơn hàng của bạn đang chờ xử lý',
            'processing' => 'Đơn hàng của bạn đang được chuẩn bị',
            'shipping' => 'Đơn hàng của bạn đang được giao',
            'completed' => 'Đơn hàng của bạn đã hoàn thành',
            'cancelled' => 'Đơn hàng của bạn đã bị hủy',
        ][$this->newStatus] ?? 'Trạng thái đơn hàng đã được cập nhật';
    }
    
}
