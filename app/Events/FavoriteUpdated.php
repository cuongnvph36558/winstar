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

class FavoriteUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $product;
    public $action; // 'added' or 'removed'
    public $favoriteCount;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, Product $product, string $action, int $favoriteCount)
    {
        $this->user = $user;
        $this->product = $product;
        $this->action = $action;
        $this->favoriteCount = $favoriteCount;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('favorites'),
            new Channel('product.' . $this->product->id),
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
            'favorite_count' => $this->favoriteCount,
            'timestamp' => now()->toISOString(),
            'message' => $this->action === 'added' 
                ? "{$this->user->name} đã thích sản phẩm \"{$this->product->name}\""
                : "{$this->user->name} đã bỏ thích sản phẩm \"{$this->product->name}\""
        ];
    }
} 