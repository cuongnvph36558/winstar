<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserActivity implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $activity;
    public $data;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, string $activity, array $data = [])
    {
        $this->user = $user;
        $this->activity = $activity;
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('user-activity'),
            new PrivateChannel('user.' . $this->user->id),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'activity' => $this->activity,
            'data' => $this->data,
            'timestamp' => now()->toISOString(),
            'message' => $this->getActivityMessage()
        ];
    }

    private function getActivityMessage(): string
    {
        switch ($this->activity) {
            case 'login':
                return "{$this->user->name} đã đăng nhập";
            case 'logout':
                return "{$this->user->name} đã đăng xuất";
            case 'view_product':
                return "{$this->user->name} đang xem sản phẩm";
            case 'add_to_cart':
                return "{$this->user->name} đã thêm sản phẩm vào giỏ hàng";
            case 'place_order':
                return "{$this->user->name} đã đặt hàng";
            default:
                return "{$this->user->name} đã thực hiện hành động: {$this->activity}";
        }
    }
} 