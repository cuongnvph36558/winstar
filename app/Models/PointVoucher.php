<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class PointVoucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'points_required',
        'discount_value',
        'discount_type',
        'min_order_value',
        'max_usage',
        'current_usage',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'points_required' => 'integer',
        'discount_value' => 'decimal:2',
        'min_order_value' => 'decimal:2',
        'max_usage' => 'integer',
        'current_usage' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function userVouchers(): HasMany
    {
        return $this->hasMany(UserPointVoucher::class);
    }

    /**
     * Kiểm tra voucher có còn hiệu lực không
     */
    public function isActive(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = Carbon::now();

        if ($this->start_date && $now->lt($this->start_date)) {
            return false;
        }

        if ($this->end_date && $now->gt($this->end_date)) {
            return false;
        }

        if ($this->max_usage && $this->current_usage >= $this->max_usage) {
            return false;
        }

        return true;
    }

    /**
     * Tính toán giá trị giảm giá
     */
    public function calculateDiscount(float $orderAmount): float
    {
        if ($this->discount_type === 'percentage') {
            $discount = $orderAmount * ($this->discount_value / 100);
        } else {
            $discount = $this->discount_value;
        }

        return $discount;
    }

    /**
     * Kiểm tra đơn hàng có đủ điều kiện sử dụng voucher không
     */
    public function canBeUsedForOrder(float $orderAmount): bool
    {
        if (!$this->isActive()) {
            return false;
        }

        if ($this->min_order_value && $orderAmount < $this->min_order_value) {
            return false;
        }

        return true;
    }

    /**
     * Tăng số lần sử dụng
     */
    public function incrementUsage(): void
    {
        $this->increment('current_usage');
    }

    /**
     * Scope để lấy voucher đang hoạt động
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', Carbon::now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', Carbon::now());
            })
            ->where(function ($q) {
                $q->whereNull('max_usage')
                    ->orWhereRaw('current_usage < max_usage');
            });
    }
}
