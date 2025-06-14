<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'variant_name',
        'image_variant',
        'color',
        'size',
        'storage',
        'price',
        'stock_quantity',
        'sku',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
