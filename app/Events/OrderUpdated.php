<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OrderUpdated implements ShouldBroadcast
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
        
        // Debug log
        Log::info('OrderStatusUpdated event created', [
            'order_id' => $order->id,
            'old_status' => $oldStatus,
            'new_status' => $this->newStatus,
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
            new Channel('client.orders')
        ];
    }

    public function broadcastAs()
    {
        return 'OrderStatusUpdated';
    }

    public function broadcastWith()
    {
        $user = $this->order->user;
        $data = [
            'order_id' => $this->order->id,
            'user_id' => $this->order->user_id,
            'order_code' => $this->order->code_order,
            'user_name' => $user && $user->name ? $user->name : 'Khách hàng',
            'user_email' => $user && $user->email ? $user->email : '',
            'user_phone' => $this->order->phone,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'status_text' => $this->getStatusText($this->newStatus),
            'total_amount' => $this->order->total_amount,
            'payment_method' => $this->order->payment_method,
            'updated_at' => $this->order->updated_at ? $this->order->updated_at->toISOString() : now()->toISOString(),
            'message' => $this->getNotificationMessage(),
            'timestamp' => now()->toISOString(),
        ];
        
        // Debug log
        Log::info('OrderStatusUpdated broadcasting data', $data);
        
        return $data;
    }

    public function getStatusText($status)
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

    public function getNotificationMessage()
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
    public function getNotificationOrder()
        {
            return [
                'order_id' => $this->order->id,
                'user_id' => $this->order->user_id,
                'order_code' => $this->order->code_order,
                'user_name' => $this->order->user->name,
                'user_email' => $this->order->user->email,
                'user_phone' => $this->order->phone,
                'old_status' => $this->oldStatus,
                'new_status' => $this->newStatus,
                'status_text' => $this->getStatusText($this->newStatus),
                'total_amount' => $this->order->total_amount,
                'payment_method' => $this->order->payment_method,
                'updated_at' => $this->order->updated_at ? $this->order->updated_at->toISOString() : now()->toISOString(),
                'message' => $this->getNotificationMessage(),
                'timestamp' => now()->toISOString(),
            ];
        }
}   
