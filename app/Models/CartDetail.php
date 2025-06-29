<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'variant_id',
        'quantity',
        'price',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class,'cart_id','id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class,'variant_id','id');
    }

    // Tính tổng tiền cho từng item
    public function getTotalPriceAttribute()
    {
        return $this->price * $this->quantity;
    }
}
