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

class OrderReceivedConfirmed implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $customer;
    public $confirmationTime;

    /**
     * Create a new event instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->customer = $order->user;
        $this->confirmationTime = now();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('admin.orders'), // Admin channel for admin dashboard
            new Channel('admin.notifications'), // Dedicated admin notifications channel
            new Channel('orders'), // Public channel for order list
            new PrivateChannel('user.' . $this->order->user_id), // Private user channel
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

        return [
            'event_type' => 'order_received_confirmed',
            'order_id' => $this->order->id,
            'order_code' => $this->order->code_order,
            'status' => $this->order->status,
            'payment_status' => $this->order->payment_status,
            'customer_name' => $this->order->receiver_name,
            'customer_phone' => $this->order->phone,
            'customer_email' => $this->customer->email ?? null,
            'total_amount' => $total_amount,
            'total_amount_formatted' => number_format($total_amount, 0, ',', '.') . 'đ',
            'subtotal' => $subtotal,
            'discount_amount' => $discount_amount,
            'shipping_fee' => $shipping_fee,
            'confirmation_time' => $this->confirmationTime->toISOString(),
            'confirmation_time_formatted' => $this->confirmationTime->format('H:i:s d/m/Y'),
            'message' => '🎉 Khách hàng đã xác nhận nhận hàng!',
            'admin_alert' => true,
            'notification_priority' => 'high',
            'is_urgent' => true,
            'action_required' => false, // No action required from admin
            'order_details' => [
                'items_count' => $this->order->orderDetails->count(),
                'created_at' => $this->order->created_at->toISOString(),
                'delivery_address' => $this->order->billing_address . ', ' . $this->order->billing_ward . ', ' . $this->order->billing_district . ', ' . $this->order->billing_city,
                'payment_method' => $this->order->payment_method,
                'has_coupon' => !is_null($this->order->coupon_id),
                'points_used' => $this->order->points_used ?? 0
            ]
        ];
    }

    /**
     * Get the broadcast event name.
     */
    public function broadcastAs(): string
    {
        return 'OrderReceivedConfirmed';
    }
}
