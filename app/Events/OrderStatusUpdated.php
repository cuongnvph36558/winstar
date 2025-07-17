<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated implements ShouldBroadcast
{
    use SerializesModels;

    public $order;
    public $oldStatus;
    public $newStatus;

    public function __construct(Order $order, $oldStatus = null, $newStatus = null)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus ?? $order->status;
    }

    public function broadcastOn()
    {
        return [
            // Public channel for general order updates
            new Channel('orders'),
            // Private channel for the specific user who owns the order
            new PrivateChannel('user.' . $this->order->user_id),
            // Admin channel for admin notifications
            new Channel('admin.orders')
        ];
    }

    public function broadcastAs()
    {
        return 'OrderStatusUpdated';
    }

    public function broadcastWith()
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
            'updated_at' => $this->order->updated_at->toISOString(),
            'message' => $this->getNotificationMessage()
        ];
    }

    private function getStatusText($status)
    {
        $statusTexts = [
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'shipping' => 'Đang giao hàng',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy'
        ];

        return $statusTexts[$status] ?? $status;
    }

    private function getNotificationMessage()
    {
        $statusMessages = [
            'pending' => 'Đơn hàng của bạn đang chờ xử lý',
            'processing' => 'Đơn hàng của bạn đang được xử lý',
            'shipping' => 'Đơn hàng của bạn đang được giao',
            'completed' => 'Đơn hàng của bạn đã hoàn thành',
            'cancelled' => 'Đơn hàng của bạn đã bị hủy'
        ];

        return $statusMessages[$this->newStatus] ?? 'Trạng thái đơn hàng đã được cập nhật';
    }
}
