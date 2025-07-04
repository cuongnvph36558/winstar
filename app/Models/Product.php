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
        'description',
        'category_id',
        'status',
        'view',
    ];

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
        return $this->hasMany(Comment::class)->where('status', 1);
    }
    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'product_id');
    }
    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'product_id');
    }
}
