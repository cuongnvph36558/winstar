<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PointTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'points',
        'description',
        'reference_type',
        'reference_id',
        'expiry_date',
        'is_expired',
    ];

    protected $casts = [
        'points' => 'integer',
        'expiry_date' => 'date',
        'is_expired' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'reference_id');
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(PointVoucher::class, 'reference_id');
    }

    /**
     * Scope để lấy giao dịch tích điểm
     */
    public function scopeEarned($query)
    {
        return $query->where('type', 'earn');
    }

    /**
     * Scope để lấy giao dịch sử dụng điểm
     */
    public function scopeUsed($query)
    {
        return $query->where('type', 'use');
    }

    /**
     * Scope để lấy giao dịch điểm hết hạn
     */
    public function scopeExpired($query)
    {
        return $query->where('type', 'expire');
    }

    /**
     * Scope để lấy giao dịch điểm thưởng
     */
    public function scopeBonus($query)
    {
        return $query->where('type', 'bonus');
    }

    /**
     * Scope để lấy giao dịch chưa hết hạn
     */
    public function scopeNotExpired($query)
    {
        return $query->where('is_expired', false);
    }

    /**
     * Scope để lấy giao dịch theo khoảng thời gian
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope để lấy giao dịch theo reference_type
     */
    public function scopeByReferenceType($query, $referenceType)
    {
        return $query->where('reference_type', $referenceType);
    }

    /**
     * Scope để lấy giao dịch từ đơn hàng
     */
    public function scopeFromOrders($query)
    {
        return $query->where('reference_type', 'order');
    }

    /**
     * Scope để lấy giao dịch từ voucher
     */
    public function scopeFromVouchers($query)
    {
        return $query->where('reference_type', 'voucher');
    }
}
