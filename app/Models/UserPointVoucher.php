<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class UserPointVoucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'point_voucher_id',
        'voucher_code',
        'status',
        'expiry_date',
        'used_in_order_id',
        'used_at',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'used_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pointVoucher(): BelongsTo
    {
        return $this->belongsTo(PointVoucher::class);
    }

    public function usedInOrder(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'used_in_order_id');
    }

    /**
     * Kiểm tra voucher có còn hiệu lực không
     */
    public function isActive(): bool
    {
        return $this->status === 'active' &&
               $this->expiry_date->isFuture() &&
               $this->pointVoucher->isActive();
    }

    /**
     * Kiểm tra voucher đã hết hạn chưa
     */
    public function isExpired(): bool
    {
        return $this->expiry_date->isPast();
    }

    /**
     * Kiểm tra voucher đã được sử dụng chưa
     */
    public function isUsed(): bool
    {
        return $this->status === 'used';
    }

    /**
     * Đánh dấu voucher đã được sử dụng
     */
    public function markAsUsed(int $orderId): void
    {
        $this->update([
            'status' => 'used',
            'used_in_order_id' => $orderId,
            'used_at' => Carbon::now(),
        ]);
    }

    /**
     * Đánh dấu voucher đã hết hạn
     */
    public function markAsExpired(): void
    {
        $this->update(['status' => 'expired']);
    }

    /**
     * Scope để lấy voucher đang hoạt động
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('expiry_date', '>', Carbon::now());
    }

    /**
     * Scope để lấy voucher đã sử dụng
     */
    public function scopeUsed($query)
    {
        return $query->where('status', 'used');
    }

    /**
     * Scope để lấy voucher đã hết hạn
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'expired')
            ->orWhere('expiry_date', '<=', Carbon::now());
    }
}
