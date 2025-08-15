<?php

namespace App\Events;

use App\Models\Order;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCancelled implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $user;
    public $cancellationReason;

    /**
     * Create a new event instance.
     */
    public function __construct(Order $order, User $user, $cancellationReason)
    {
        $this->order = $order;
        $this->user = $user;
        $this->cancellationReason = $cancellationReason;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('admin.orders'),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'order_id' => $this->order->id,
            'order_code' => $this->order->code_order,
            'user_name' => $this->user->name,
            'user_email' => $this->user->email,
            'total_amount' => $this->order->total_amount,
            'cancellation_reason' => $this->cancellationReason,
            'cancelled_at' => $this->order->cancelled_at,
            'message' => "Đơn hàng #{$this->order->code_order} đã bị hủy bởi {$this->user->name}"
        ];
    }
}
