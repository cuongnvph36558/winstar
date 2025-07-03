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
        'description',
        'category_id',
        'status',
        'view',
    ];

    // Accessor để tương thích với view
    public function getImageUrlAttribute()
    {
        return $this->image;
    }

    public function getComparePriceAttribute()
    {
        // Giả sử compare_price cao hơn price 20%
        return $this->price ? $this->price * 1.2 : null;
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
        return $this->hasMany(Comment::class);
    }
}
