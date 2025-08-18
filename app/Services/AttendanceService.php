<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\User;
use App\Models\PointTransaction;
use App\Mail\AttendanceThankYouMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AttendanceService
{
    /**
     * Điểm danh vào
     */
    public function checkIn(User $user): array
    {
        try {
            DB::beginTransaction();

            $today = now()->toDateString();
            $currentTime = now();

            // Kiểm tra xem đã điểm danh hôm nay chưa
            $existingAttendance = Attendance::where('user_id', $user->id)
                ->where('date', $today)
                ->first();

            if ($existingAttendance) {
                if ($existingAttendance->check_in_time) {
                    DB::rollBack();
                    return [
                        'success' => false,
                        'message' => 'Bạn đã điểm danh vào hôm nay rồi!'
                    ];
                }
            }

            // Tạo hoặc cập nhật bản ghi điểm danh
            $attendance = Attendance::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'date' => $today
                ],
                [
                    'check_in_time' => $currentTime->format('H:i:s'),
                    'status' => 'present',
                    'points_earned' => 0 // Sẽ tính sau khi check out
                ]
            );

            DB::commit();

            // Gửi email cảm ơn điểm danh vào
            try {
                Mail::to($user->email)->send(new AttendanceThankYouMail($user, $attendance, 'check_in'));
            } catch (\Exception $e) {
                Log::error('Error sending attendance check-in email: ' . $e->getMessage());
            }

            return [
                'success' => true,
                'message' => 'Điểm danh vào thành công!',
                'data' => $attendance
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in AttendanceService@checkIn: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi điểm danh vào!'
            ];
        }
    }

    /**
     * Điểm danh ra
     */
    public function checkOut(User $user): array
    {
        try {
            DB::beginTransaction();

            $today = now()->toDateString();
            $currentTime = now();

            // Tìm bản ghi điểm danh hôm nay
            $attendance = Attendance::where('user_id', $user->id)
                ->where('date', $today)
                ->first();

            if (!$attendance) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'Bạn chưa điểm danh vào hôm nay!'
                ];
            }

            if ($attendance->check_out_time) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'Bạn đã điểm danh ra hôm nay rồi!'
                ];
            }

            // Cập nhật thời gian check out
            $attendance->check_out_time = $currentTime->format('H:i:s');

            // Tính điểm tích được (chỉ để hiển thị, không tự động cộng)
            $pointsEarned = $this->calculateAttendancePoints($attendance);
            $attendance->points_earned = $pointsEarned;
            $attendance->save();

            // KHÔNG tự động cộng điểm - người dùng phải tự tích

            DB::commit();

            // Gửi email cảm ơn điểm danh ra
            try {
                Mail::to($user->email)->send(new AttendanceThankYouMail($user, $attendance, 'check_out'));
            } catch (\Exception $e) {
                Log::error('Error sending attendance check-out email: ' . $e->getMessage());
            }

            return [
                'success' => true,
                'message' => "Điểm danh ra thành công! Bạn có thể tích {$pointsEarned} điểm.",
                'data' => $attendance
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in AttendanceService@checkOut: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi điểm danh ra!'
            ];
        }
    }

    /**
     * Tính điểm tích được từ điểm danh
     */
    public function calculateAttendancePoints(Attendance $attendance): int
    {
        if (!$attendance->check_in_time || !$attendance->check_out_time) {
            return 0;
        }

        try {
            // Lấy ngày hiện tại và kết hợp với thời gian check in/out
            $today = $attendance->date;
            
            // Đảm bảo format thời gian đúng
            $checkInTime = is_string($attendance->check_in_time) ? $attendance->check_in_time : $attendance->check_in_time->format('H:i:s');
            $checkOutTime = is_string($attendance->check_out_time) ? $attendance->check_out_time : $attendance->check_out_time->format('H:i:s');
            
            $checkIn = Carbon::parse($today . ' ' . $checkInTime);
            $checkOut = Carbon::parse($today . ' ' . $checkOutTime);
        
        // Nếu check out trước check in (qua ngày), cộng thêm 1 ngày
        if ($checkOut < $checkIn) {
            $checkOut->addDay();
        }
        
        $workMinutes = $checkIn->diffInMinutes($checkOut);
        $workHours = $workMinutes / 60;

        // Tính điểm dựa trên thời gian làm việc
        // 1 giờ = 10 điểm
        $basePoints = (int)($workHours * 10);

        // Bonus điểm cho việc điểm danh đúng giờ
        $bonusPoints = 0;
        
        // Điểm danh vào trước 9h sáng: +5 điểm
        if ($checkIn->hour < 9) {
            $bonusPoints += 5;
        }
        
        // Làm việc trên 8 giờ: +10 điểm
        if ($workHours >= 8) {
            $bonusPoints += 10;
        }

        // Làm việc trên 6 giờ: +5 điểm
        elseif ($workHours >= 6) {
            $bonusPoints += 5;
        }

        return $basePoints + $bonusPoints;
        } catch (\Exception $e) {
            // Log lỗi và trả về 0 điểm
            \Log::error('Error calculating attendance points: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Cộng điểm vào tài khoản user
     */
    public function addPointsToUser(User $user, int $points, Attendance $attendance): void
    {
        // Cập nhật bảng points
        $userPoint = $user->point;
        if (!$userPoint) {
            $userPoint = $user->point()->create([
                'total_points' => 0,
                'earned_points' => 0,
                'used_points' => 0,
                'expired_points' => 0,
                'vip_level' => 'Bronze'
            ]);
        }

        $userPoint->total_points += $points;
        $userPoint->earned_points += $points;
        $userPoint->save();

        // Tạo giao dịch điểm
        PointTransaction::create([
            'user_id' => $user->id,
            'type' => 'earn',
            'points' => $points,
            'description' => "Điểm danh ngày " . $attendance->date->format('d/m/Y') . " - Làm việc " . $attendance->work_hours . " giờ",
            'reference_type' => 'attendance',
            'reference_id' => $attendance->id,
            'expiry_date' => now()->addYear(), // Điểm có hiệu lực 1 năm
            'is_expired' => false
        ]);
    }

    /**
     * Lấy thống kê điểm danh của user
     */
    public function getUserAttendanceStats(User $user, $month = null, $year = null): array
    {
        $query = Attendance::where('user_id', $user->id);

        if ($month && $year) {
            $query->inMonth($year, $month);
        } elseif ($month) {
            $query->inMonth(now()->year, $month);
        } elseif ($year) {
            $query->whereYear('date', $year);
        }

        $attendanceRecords = $query->get();

        $totalDays = $attendanceRecords->count();
        $completedDays = $attendanceRecords->where('check_out_time', '!=', null)->count();
        $totalPoints = $attendanceRecords->sum('points_earned');
        $totalWorkHours = $attendanceRecords->sum(function ($record) {
            return $record->work_hours;
        });

        // Tính tỷ lệ điểm danh
        $attendanceRate = $totalDays > 0 ? round(($completedDays / $totalDays) * 100, 2) : 0;

        return [
            'total_days' => $totalDays,
            'completed_days' => $completedDays,
            'total_points' => $totalPoints,
            'total_work_hours' => round($totalWorkHours, 2),
            'attendance_rate' => $attendanceRate,
            'records' => $attendanceRecords
        ];
    }

    /**
     * Lấy lịch sử điểm danh của user
     */
    public function getUserAttendanceHistory(User $user, int $limit = 30): array
    {
        $attendanceRecords = Attendance::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->limit($limit)
            ->get();

        return $attendanceRecords->map(function ($record) {
            return [
                'id' => $record->id,
                'date' => $record->date->format('d/m/Y'),
                'check_in_time' => $record->check_in_time ? $record->check_in_time : null,
                'check_out_time' => $record->check_out_time ? $record->check_out_time : null,
                'work_hours' => $record->work_hours,
                'points_earned' => $record->points_earned,
                'status' => $record->status,
                'is_completed' => $record->isCompleted(),
                'can_check_out' => $record->canCheckOut()
            ];
        })->toArray();
    }

    /**
     * Kiểm tra trạng thái điểm danh hôm nay
     */
    public function getTodayAttendanceStatus(User $user): array
    {
        $today = now()->toDateString();
        
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if (!$attendance) {
            return [
                'has_checked_in' => false,
                'has_checked_out' => false,
                'can_check_in' => true,
                'can_check_out' => false,
                'points_earned' => 0,
                'work_hours' => 0,
                'can_claim_points' => false
            ];
        }

        return [
            'has_checked_in' => (bool)$attendance->check_in_time,
            'has_checked_out' => (bool)$attendance->check_out_time,
            'can_check_in' => !$attendance->check_in_time,
            'can_check_out' => $attendance->canCheckOut(),
            'points_earned' => $attendance->points_earned,
            'work_hours' => $attendance->work_hours,
            'check_in_time' => $attendance->check_in_time ? $attendance->check_in_time : null,
            'check_out_time' => $attendance->check_out_time ? $attendance->check_out_time : null,
            'can_claim_points' => $attendance->check_out_time && $attendance->points_earned > 0 && !$attendance->points_claimed
        ];
    }

    /**
     * Người dùng tự tích điểm từ điểm danh
     */
    public function claimAttendancePoints(User $user): array
    {
        try {
            DB::beginTransaction();

            $today = now()->toDateString();
            
            $attendance = Attendance::where('user_id', $user->id)
                ->where('date', $today)
                ->first();

            if (!$attendance) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'Không tìm thấy bản ghi điểm danh hôm nay!'
                ];
            }

            if (!$attendance->check_out_time) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'Bạn chưa điểm danh ra hôm nay!'
                ];
            }

            if ($attendance->points_earned <= 0) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'Không có điểm để tích!'
                ];
            }

            if ($attendance->points_claimed) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'Bạn đã tích điểm hôm nay rồi!'
                ];
            }

            // Cộng điểm vào tài khoản user
            $this->addPointsToUser($user, $attendance->points_earned, $attendance);

            // Đánh dấu đã tích điểm
            $attendance->points_claimed = true;
            $attendance->save();

            DB::commit();

            return [
                'success' => true,
                'message' => "Tích điểm thành công! Bạn nhận được {$attendance->points_earned} điểm.",
                'data' => $attendance
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in AttendanceService@claimAttendancePoints: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tích điểm!'
            ];
        }
    }
} 