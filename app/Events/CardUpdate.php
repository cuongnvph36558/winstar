<?php

namespace App\Events;

use App\Models\Product;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CardUpdate implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $product;
    public $action; // 'added' or 'removed'
    public $cartCount;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, Product $product, string $action, int $cartCount)
    {
        $this->user = $user;
        $this->product = $product;
        $this->action = $action;
        $this->cartCount = $cartCount;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('cart-updates'),
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
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'action' => $this->action,
            'cart_count' => $this->cartCount,
            'timestamp' => now()->toISOString(),
            'message' => $this->action === 'added' 
                ? "{$this->user->name} đã thêm sản phẩm \"{$this->product->name}\" vào giỏ hàng"
                : "{$this->user->name} đã xóa sản phẩm \"{$this->product->name}\" khỏi giỏ hàng"
        ];
    }
}