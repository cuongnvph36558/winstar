<?php

namespace App\Events;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductStockUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $product;
    public $variant;
    public $oldStock;
    public $newStock;

    /**
     * Create a new event instance.
     */
    public function __construct(Product $product, ProductVariant $variant = null, int $oldStock, int $newStock)
    {
        $this->product = $product;
        $this->variant = $variant;
        $this->oldStock = $oldStock;
        $this->newStock = $newStock;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('product-stock'),
            new Channel('product.' . $this->product->id),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'variant_id' => $this->variant ? $this->variant->id : null,
            'variant_name' => $this->variant ? $this->variant->variant_name : null,
            'old_stock' => $this->oldStock,
            'new_stock' => $this->newStock,
            'stock_change' => $this->newStock - $this->oldStock,
            'timestamp' => now()->toISOString(),
            'message' => $this->variant 
                ? "Tồn kho biến thể \"{$this->variant->variant_name}\" của sản phẩm \"{$this->product->name}\" đã thay đổi từ {$this->oldStock} thành {$this->newStock}"
                : "Tồn kho sản phẩm \"{$this->product->name}\" đã thay đổi từ {$this->oldStock} thành {$this->newStock}"
        ];
    }
} 