<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Events\OrderStatusUpdated;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'code_order',
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
        'is_received',
        'return_status',
        'return_reason',
        'return_description',
        'return_requested_at',
        'return_processed_at',
                        'return_method',
                'return_amount',
                'admin_return_note',
                'return_video',
                'return_order_image',
                'return_product_image',
        'coupon_id',
        'discount_amount',
        'payment_status',
        'points_earned',
        'points_used',
        'point_voucher_code',
        'cancellation_reason',
        'cancelled_at'
    ];

    protected $casts = [
        'cancelled_at' => 'datetime',
        'return_requested_at' => 'datetime',
        'return_processed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Đơn hàng có nhiều chi tiết.
     */
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * Alias for orderDetails for backward compatibility
     */
    /**
     * Đơn hàng thuộc về người dùng.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    protected static function booted()
    {
        static::updating(function ($order) {
            if ($order->isDirty('status')) {
                $oldStatus = $order->getOriginal('status');
                $newStatus = $order->status;

                // Trigger event khi trạng thái thay đổi
                try {
            event(new OrderStatusUpdated($order, $oldStatus, $newStatus));
        } catch (\Exception $e) {
            \Log::warning('Failed to broadcast OrderStatusUpdated event: ' . $e->getMessage());
        }
            }
        });
    }
}
