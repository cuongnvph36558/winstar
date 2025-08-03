<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\PointTransaction;
use Illuminate\Support\Facades\DB;

class UpdateUserPoints extends Command
{
    protected $signature = 'points:update-user {email}';
    protected $description = 'Cập nhật điểm cho user theo email';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("Không tìm thấy user với email: {$email}");
            return 1;
        }

        $this->info("Đang cập nhật điểm cho user: {$user->name}");

        try {
            // Tính lại tổng điểm từ các giao dịch
            $totalEarned = PointTransaction::where('user_id', $user->id)
                ->where('type', 'earn')
                ->where('is_expired', false)
                ->sum('points');

            $totalUsed = PointTransaction::where('user_id', $user->id)
                ->where('type', 'use')
                ->sum(DB::raw('ABS(points)'));

            $totalExpired = PointTransaction::where('user_id', $user->id)
                ->where('type', 'earn')
                ->where('is_expired', true)
                ->sum('points');

            $totalPoints = $totalEarned - $totalUsed;

            // Cập nhật hoặc tạo point record
            $point = $user->point;
            if (!$point) {
                $point = $user->point()->create([
                    'total_points' => max(0, $totalPoints),
                    'earned_points' => $totalEarned,
                    'used_points' => $totalUsed,
                    'expired_points' => $totalExpired,
                ]);
            } else {
                $point->update([
                    'total_points' => max(0, $totalPoints),
                    'earned_points' => $totalEarned,
                    'used_points' => $totalUsed,
                    'expired_points' => $totalExpired,
                ]);
            }

            $this->info("Đã cập nhật điểm thành công!");
            $this->info("Tổng điểm: " . number_format($point->total_points));
            $this->info("Điểm đã tích: " . number_format($point->earned_points));
            $this->info("Điểm đã dùng: " . number_format($point->used_points));
            $this->info("Điểm đã hết hạn: " . number_format($point->expired_points));

            return 0;
        } catch (\Exception $e) {
            $this->error('Lỗi: ' . $e->getMessage());
            return 1;
        }
    }
} 