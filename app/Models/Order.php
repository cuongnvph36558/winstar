<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'receiver_name',
        'billing_city',
        'billing_district',
        'billing_ward', 
        'billing_address',
        'phone',
        'description',
        'total_amount',
        'payment_method',
        'status',
        'coupon_id',
        'payment_status'
    ];

    /**
     * Đơn hàng có nhiều chi tiết.
     */
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * Đơn hàng thuộc về người dùng.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }
}
