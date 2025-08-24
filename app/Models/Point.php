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

        if ($totalEarned >= 700000) return 'Diamond'; // Tăng từ 600k lên 700k (+100k)
        if ($totalEarned >= 490000) return 'Platinum'; // Tăng từ 390k lên 490k (+100k)
        if ($totalEarned >= 430000) return 'Gold'; // Tăng từ 330k lên 430k (+100k)
        if ($totalEarned >= 340000) return 'Silver'; // Tăng từ 240k lên 340k (+100k)

        return 'Bronze';
    }

    /**
     * Lấy tỷ lệ tích điểm dựa trên level VIP
     */
    public function getPointRateAttribute(): float
    {
        $level = $this->vip_level;

        return match($level) {
            'Diamond' => 0.20, // 20% giá trị đơn hàng
            'Platinum' => 0.13, // 13% giá trị đơn hàng
            'Gold' => 0.11, // 11% giá trị đơn hàng
            'Silver' => 0.08, // 8% giá trị đơn hàng
            default => 0.05, // 5% giá trị đơn hàng (Bronze)
        };
    }
}
