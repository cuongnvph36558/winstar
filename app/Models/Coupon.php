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

    /**
     * Coupon có nhiều người dùng sử dụng.
     */
    public function couponUsers()
    {
        return $this->hasMany(CouponUser::class);
    }

    /**
     * Lấy số lần sử dụng của coupon
     */
    public function getUsageCountAttribute()
    {
        return $this->couponUsers()->count();
    }

    /**
     * Kiểm tra xem coupon có hết hạn sử dụng không
     */
    public function isExhausted()
    {
        if (!$this->usage_limit) {
            return false;
        }
        return $this->getUsageCountAttribute() >= $this->usage_limit;
    }
}