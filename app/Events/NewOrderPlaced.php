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

class NewOrderPlaced implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
        
        // Debug log
        Log::info('NewOrderPlaced event created', [
            'order_id' => $order->id,
            'order_code' => $order->code_order,
            'user_id' => $order->user_id,
            'total_amount' => $order->total_amount
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
        return 'NewOrderPlaced';
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
                'status' => $this->order->status,
                'status_text' => $this->getStatusText($this->order->status),
                'total_amount' => $this->order->total_amount ?? 0,
                'payment_method' => $this->order->payment_method ?? '',
                'receiver_name' => $this->order->receiver_name ?? '',
                'billing_address' => $this->order->billing_address ?? '',
                'billing_ward' => $this->order->billing_ward ?? '',
                'billing_district' => $this->order->billing_district ?? '',
                'billing_city' => $this->order->billing_city ?? '',
                'created_at' => $this->order->created_at ? $this->order->created_at->toISOString() : now()->toISOString(),
                'message' => $this->getMessage(),
                'timestamp' => now()->toISOString(),
                'is_new_order' => true,
                'notification_type' => 'new_order',
            ];
            
            Log::info('NewOrderPlaced broadcasting data', $data);
            Log::info('NewOrderPlaced address details', [
                'billing_address' => $this->order->billing_address,
                'billing_ward' => $this->order->billing_ward,
                'billing_district' => $this->order->billing_district,
                'billing_city' => $this->order->billing_city,
            ]);
            
            return $data;
        } catch (\Exception $e) {
            Log::error('Error in NewOrderPlaced broadcastWith: ' . $e->getMessage());
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
            'received' => 'Đã nhận hàng',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy'
        ];

        return $statusTexts[$status] ?? $status;
    }
    
    private function getMessage()
    {
        return "🎉 Đơn hàng mới! #{$this->order->code_order} từ " . 
               ($this->order->user && $this->order->user->name ? $this->order->user->name : 'Khách hàng') . 
               " - " . number_format($this->order->total_amount, 0, ',', '.') . "₫";
    }
} 