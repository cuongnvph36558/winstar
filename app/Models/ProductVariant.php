<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'image_variant',
        'price',
        'promotion_price',
        'stock_quantity',
        'color_id',
        'storage_id',
    ];

    protected $casts = [
        'stock_quantity' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id');
    }

    public function storage(): BelongsTo
    {
        return $this->belongsTo(Storage::class, 'storage_id');
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'variant_id');
    }
    
    // accessor để tự động tạo tên biến thể từ product + color + storage
    public function getVariantNameAttribute()
    {
        $name = $this->product->name ?? '';
        $color = $this->color->name ?? '';
        $storage = $this->storage->capacity ?? '';
        
        $parts = array_filter([$name, $color, $storage]);
        return implode(' ', $parts);
    }
    
    // accessor để map stock thành stock_quantity
    public function getStockAttribute()
    {
        return $this->stock_quantity;
    }
    
    // mutator để map stock thành stock_quantity
    public function setStockAttribute($value)
    {
        $this->stock_quantity = $value;
    }

    protected static function booted()
    {
        // đảm bảo stock_quantity không bao giờ âm

    }
}
