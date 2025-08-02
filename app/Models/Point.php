<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Point extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_points',
        'earned_points',
        'used_points',
        'expired_points',
        'vip_level',
    ];

    protected $casts = [
        'total_points' => 'integer',
        'earned_points' => 'integer',
        'used_points' => 'integer',
        'expired_points' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(PointTransaction::class, 'user_id', 'user_id');
    }

    /**
     * Lấy điểm hiện tại có thể sử dụng
     */
    public function getAvailablePointsAttribute(): int
    {
        return $this->total_points;
    }

    /**
     * Kiểm tra có đủ điểm để đổi voucher không
     */
    public function hasEnoughPoints(int $requiredPoints): bool
    {
        return $this->total_points >= $requiredPoints;
    }

    /**
     * Tính toán level VIP dựa trên tổng điểm đã tích
     */
    public function getVipLevelAttribute(): string
    {
        // Nếu có giá trị trong database, sử dụng nó
        if (isset($this->attributes['vip_level']) && $this->attributes['vip_level']) {
            return $this->attributes['vip_level'];
        }

        // Tính toán level VIP dựa trên tổng điểm đã tích
        $totalEarned = $this->earned_points;

        if ($totalEarned >= 10000) return 'Diamond';
        if ($totalEarned >= 5000) return 'Platinum';
        if ($totalEarned >= 2000) return 'Gold';
        if ($totalEarned >= 500) return 'Silver';

        return 'Bronze';
    }

    /**
     * Lấy tỷ lệ tích điểm dựa trên level VIP
     */
    public function getPointRateAttribute(): float
    {
        $level = $this->vip_level;

        return match($level) {
            'Diamond' => 0.15, // 15% giá trị đơn hàng
            'Platinum' => 0.12, // 12% giá trị đơn hàng
            'Gold' => 0.10, // 10% giá trị đơn hàng
            'Silver' => 0.08, // 8% giá trị đơn hàng
            default => 0.05, // 5% giá trị đơn hàng
        };
    }
}
