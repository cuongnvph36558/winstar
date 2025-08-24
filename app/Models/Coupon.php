<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
        'min_order_value',
        'max_discount_value',
        'max_discount',
        'usage_limit',
        'usage_limit_per_user',
        'used_count',
        'status',
        'exchange_points',
        'vip_level',
        'validity_days'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'discount_value' => 'decimal:2',
        'min_order_value' => 'decimal:2',
        'max_discount_value' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'exchange_points' => 'integer',
        'used_count' => 'integer',
    ];

    /**
     * Boot method to set default values
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($coupon) {
            if (is_null($coupon->exchange_points)) {
                $coupon->exchange_points = 0;
            }
        });

        static::updating(function ($coupon) {
            if (is_null($coupon->exchange_points)) {
                $coupon->exchange_points = 0;
            }
        });
    }

    /**
     * Set the exchange_points attribute
     */
    public function setExchangePointsAttribute($value)
    {
        // Handle null, empty string, or non-numeric values
        if (is_null($value) || $value === '' || !is_numeric($value)) {
            $this->attributes['exchange_points'] = 0;
        } else {
            $this->attributes['exchange_points'] = (int) $value;
        }
    }

    /**
     * Get the exchange_points attribute
     */
    public function getExchangePointsAttribute($value)
    {
        return $value ?? 0;
    }

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