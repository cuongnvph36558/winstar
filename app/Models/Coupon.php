<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
        'min_order_value',
        'max_discount_value',
        'usage_limit',
        'usage_limit_per_user',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'discount_value' => 'decimal:2',
        'min_order_value' => 'decimal:2',
        'max_discount_value' => 'decimal:2',
    ];

    /**
     * Coupon có nhiều đơn hàng.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}