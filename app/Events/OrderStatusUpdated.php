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
            // Public channel for all order updates
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
        try {
            $user = $this->order->user;
            $data = [
                'order_id' => $this->order->id,
                'order_code' => $this->order->code_order,
                'user_id' => $this->order->user_id,
                'user_name' => $user && $user->name ? $user->name : 'Khách hàng',
                'user_email' => $user && $user->email ? $user->email : '',
                'user_phone' => $this->order->phone ?? '',
                'old_status' => $this->oldStatus,
                'new_status' => $this->newStatus,
                'status_text' => $this->getStatusText($this->newStatus),
                'total_amount' => $this->order->total_amount ?? 0,
                'payment_method' => $this->order->payment_method ?? '',
                'updated_at' => $this->order->updated_at ? $this->order->updated_at->toISOString() : now()->toISOString(),
                'message' => $this->getStatusMessage(),
                'timestamp' => now()->toISOString(),
                'is_new_order' => $this->isNewOrder(),
            ];
            
            Log::info('OrderStatusUpdated broadcasting data', $data);
            
            return $data;
        } catch (\Exception $e) {
            Log::error('Error in OrderStatusUpdated broadcastWith: ' . $e->getMessage());
            return [
                'order_id' => $this->order->id,
                'error' => 'Error processing order data'
            ];
        }
    }
    
    private function getStatusText($status)
    {
        $statusTexts = [
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang chuẩn bị hàng',
            'shipping' => 'Đang giao hàng',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy'
        ];

        return $statusTexts[$status] ?? $status;
    }
    
    private function getStatusMessage()
    {
        if ($this->isNewOrder()) {
            return "🎉 Đặt hàng thành công! Đơn hàng #{$this->order->code_order} đã được xác nhận và đang chờ xử lý.";
        }

        $statusMessages = [
            'pending' => 'Đơn hàng của bạn đang chờ xử lý',
            'processing' => 'Đơn hàng của bạn đang được xử lý',
            'shipping' => 'Đơn hàng của bạn đang được giao',
            'completed' => 'Đơn hàng của bạn đã hoàn thành',
            'cancelled' => 'Đơn hàng của bạn đã bị hủy'
        ];

        return $statusMessages[$this->newStatus] ?? 'Trạng thái đơn hàng đã được cập nhật';
    }

    private function isNewOrder()
    {
        // Kiểm tra nếu đây là đơn hàng mới được tạo (từ null/empty sang pending)
        return ($this->oldStatus === null || $this->oldStatus === '') && $this->newStatus === 'pending';
    }
}
