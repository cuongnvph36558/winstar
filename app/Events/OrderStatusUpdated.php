<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class OrderStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $oldStatus;
    public $newStatus;
    public $actionBy; // 'admin' or 'client'
    public $actionDetails;

    /**
     * Create a new event instance.
     */
    public function __construct(Order $order, $oldStatus, $newStatus, $actionBy = 'admin', $actionDetails = [])
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->actionBy = $actionBy;
        $this->actionDetails = $actionDetails;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('order.' . $this->order->id),
            new PrivateChannel('user.' . $this->order->user_id),
            new Channel('orders'), // Public channel for order list
            new Channel('admin.orders'), // Admin channel for admin dashboard
            new Channel('admin.notifications'), // Dedicated admin notifications channel
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        // Calculate order totals
        $subtotal = $this->order->orderDetails->sum(function($detail) {
            return $detail->price * $detail->quantity;
        });
        
        $discount_amount = $this->order->discount_amount ?? 0;
        $shipping_fee = $this->order->shipping_fee ?? 30000;
        $total_amount = $subtotal - $discount_amount + $shipping_fee;

        // Determine action type and message
        $actionType = 'status_updated';
        $actionMessage = 'Trạng thái đơn hàng đã được cập nhật';
        
        if ($this->newStatus === 'completed' && $this->order->is_received) {
            $actionType = 'client_confirmed_received';
            $actionMessage = 'Khách hàng đã xác nhận nhận hàng!';
        } elseif ($this->actionBy === 'client') {
            $actionType = 'client_action';
            $actionMessage = 'Khách hàng đã thực hiện hành động';
        }

        return [
            'order_id' => $this->order->id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'status' => $this->newStatus,
            'payment_status' => $this->order->payment_status,
            'subtotal' => $subtotal,
            'discount_amount' => $discount_amount,
            'shipping_fee' => $shipping_fee,
            'total_amount' => $total_amount,
            'order_code' => $this->order->code_order,
            'customer_name' => $this->order->user->name ?? $this->order->receiver_name,
            'phone' => $this->order->phone,
            'created_at' => $this->order->created_at->toISOString(),
            'updated_at' => $this->order->updated_at->toISOString(),
            'message' => $actionMessage,
            'action_by' => $this->actionBy,
            'action_type' => $actionType,
            'is_client_action' => $this->order->is_received,
            'is_urgent' => $actionType === 'client_confirmed_received', // Mark as urgent for admin
            'notification_priority' => $actionType === 'client_confirmed_received' ? 'high' : 'normal',
            'admin_alert' => $actionType === 'client_confirmed_received' ? true : false,
            'customer_phone' => $this->order->phone,
            'customer_email' => $this->order->user->email ?? null,
            'order_total_formatted' => number_format($total_amount, 0, ',', '.') . 'đ',
            'action_timestamp' => now()->toISOString(),
            'action_details' => $this->actionDetails
        ];
    }

    /**
     * Get the broadcast event name.
     */
    public function broadcastAs(): string
    {
        return 'OrderStatusUpdated';
    }
}
