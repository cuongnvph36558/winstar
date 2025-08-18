<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance';

    protected $fillable = [
        'user_id',
        'date',
        'check_in_time',
        'check_out_time',
        'points_earned',
        'status',
        'notes',
        'points_claimed'
    ];

    protected $casts = [
        'date' => 'date',
        'check_in_time' => 'string',
        'check_out_time' => 'string',
        'points_earned' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope để lấy điểm danh theo tháng
     */
    public function scopeInMonth($query, $year, $month)
    {
        return $query->whereYear('date', $year)->whereMonth('date', $month);
    }

    /**
     * Scope để lấy điểm danh theo tuần
     */
    public function scopeInWeek($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Scope để lấy điểm danh hiện tại
     */
    public function scopeToday($query)
    {
        return $query->where('date', now()->toDateString());
    }

    /**
     * Scope để lấy điểm danh đã hoàn thành (có check in và check out)
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('check_in_time')->whereNotNull('check_out_time');
    }

    /**
     * Kiểm tra xem điểm danh đã hoàn thành chưa
     */
    public function isCompleted(): bool
    {
        return $this->check_in_time && $this->check_out_time;
    }

    /**
     * Kiểm tra xem có thể điểm danh ra không
     */
    public function canCheckOut(): bool
    {
        return $this->check_in_time && !$this->check_out_time;
    }

    /**
     * Tính tổng thời gian làm việc (phút)
     */
    public function getWorkMinutesAttribute(): int
    {
        if (!$this->check_in_time || !$this->check_out_time) {
            return 0;
        }

        // Kết hợp ngày với thời gian
        $checkIn = \Carbon\Carbon::parse($this->date . ' ' . $this->check_in_time);
        $checkOut = \Carbon\Carbon::parse($this->date . ' ' . $this->check_out_time);
        
        // Nếu check out trước check in (qua ngày), cộng thêm 1 ngày
        if ($checkOut < $checkIn) {
            $checkOut->addDay();
        }
        
        return $checkIn->diffInMinutes($checkOut);
    }

    /**
     * Tính tổng thời gian làm việc (giờ)
     */
    public function getWorkHoursAttribute(): float
    {
        return round($this->work_minutes / 60, 2);
    }
}
