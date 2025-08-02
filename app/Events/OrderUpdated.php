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
        Log::info('OrderUpdated event created', [
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
            // User-specific private channel for personal notifications
            new PrivateChannel('user.' . $this->order->user_id),
            // Public channel for real-time order notifications
            new Channel('order-notifications')
        ];
    }

    public function broadcastAs()
    {
        return 'OrderUpdated';
    }

    public function broadcastWith()
    {
        try {
            $user = $this->order->user;
            $data = [
                'order_id' => $this->order->id,
                'user_id' => $this->order->user_id,
                'order_code' => $this->order->code_order ?? '',
                'user_name' => $user && $user->name ? $user->name : 'Khách hàng',
                'user_email' => $user && $user->email ? $user->email : '',
                'user_phone' => $this->order->phone ?? '',
                'old_status' => $this->oldStatus,
                'new_status' => $this->newStatus,
                'status_text' => $this->getStatusText($this->newStatus),
                'total_amount' => $this->order->total_amount ?? 0,
                'payment_method' => $this->order->payment_method ?? '',
                'updated_at' => $this->order->updated_at ? $this->order->updated_at->toISOString() : now()->toISOString(),
                'message' => $this->getNotificationMessage(),
                'timestamp' => now()->toISOString(),
                'notification_type' => $this->getNotificationType(),
                'is_purchase_success' => $this->isPurchaseSuccess(),
            ];
            
            // Debug log
            Log::info('OrderStatusUpdated broadcasting data', $data);
            
            return $data;
        } catch (\Exception $e) {
            Log::error('Error in OrderUpdated broadcastWith: ' . $e->getMessage());
            return [
                'order_id' => $this->order->id,
                'error' => 'Error processing order data'
            ];
        }
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
        if ($this->isPurchaseSuccess()) {
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

    public function getNotificationType()
    {
        if ($this->isPurchaseSuccess()) {
            return 'purchase_success';
        }

        return 'status_update';
    }

    public function isPurchaseSuccess()
    {
        // Kiểm tra nếu đây là đơn hàng mới được tạo (từ null/empty sang pending)
        return ($this->oldStatus === null || $this->oldStatus === '') && $this->newStatus === 'pending';
    }

    public function getNotificationOrder()
    {
        try {
            $user = $this->order->user;
            return [
                'order_id' => $this->order->id,
                'user_id' => $this->order->user_id,
                'order_code' => $this->order->code_order ?? '',
                'user_name' => $user && $user->name ? $user->name : 'Khách hàng',
                'user_email' => $user && $user->email ? $user->email : '',
                'user_phone' => $this->order->phone ?? '',
                'old_status' => $this->oldStatus,
                'new_status' => $this->newStatus,
                'status_text' => $this->getStatusText($this->newStatus),
                'total_amount' => $this->order->total_amount ?? 0,
                'payment_method' => $this->order->payment_method ?? '',
                'updated_at' => $this->order->updated_at ? $this->order->updated_at->toISOString() : now()->toISOString(),
                'message' => $this->getNotificationMessage(),
                'timestamp' => now()->toISOString(),
                'notification_type' => $this->getNotificationType(),
                'is_purchase_success' => $this->isPurchaseSuccess(),
            ];
        } catch (\Exception $e) {
            Log::error('Error in getNotificationOrder: ' . $e->getMessage());
            return [
                'order_id' => $this->order->id,
                'error' => 'Error processing order data'
            ];
        }
    }
}   
