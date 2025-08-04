<?php

namespace App\Events;

use App\Models\Review;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewReviewAdded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $review;

    /**
     * Create a new event instance.
     */
    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('reviews'),
            new Channel('admin.reviews'),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'review_id' => $this->review->id,
            'user_id' => $this->review->user_id,
            'user_name' => $this->review->user->name ?? $this->review->name ?? 'Khách hàng',
            'product_id' => $this->review->product_id,
            'product_name' => $this->review->product->name ?? 'Sản phẩm',
            'rating' => $this->review->rating,
            'content' => $this->review->content,
            'created_at' => $this->review->created_at->toISOString(),
        ];
    }
}
