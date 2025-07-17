<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'image',
        'price',
        'promotion_price',
        'description',
        'category_id',
        'status',
        'view',
        'stock_quantity',
    ];

    // Accessor để tương thích với view
    public function getImageUrlAttribute()
    {
        return $this->image;
    }

    public function getComparePriceAttribute()
    {
        // Giả sử compare_price cao hơn price 15-25%
        return $this->price ? round($this->price * 1.2, -3) : null; // Làm tròn đến hàng nghìn
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }
    public function activeComments()
    {
            return $this->hasMany(Comment::class)
                ->where('status', 1)
                ->orderByDesc('created_at');
    }
    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'product_id');
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
