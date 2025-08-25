<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Point;
use App\Models\PointTransaction;
use Illuminate\Support\Facades\DB;

class FixPointData extends Command
{
    protected $signature = 'points:fix-data';
    protected $description = 'Sửa lại dữ liệu điểm trong database để đảm bảo tính nhất quán';

    public function handle()
    {
        $this->info('Bắt đầu sửa dữ liệu điểm...');

        try {
            DB::beginTransaction();

            $users = User::all();
            $fixedCount = 0;

            foreach ($users as $user) {
                // Tính toán lại từ giao dịch
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
                    $point = Point::create([
                        'user_id' => $user->id,
                        'total_points' => max(0, $totalPoints),
                        'earned_points' => $totalEarned,
                        'used_points' => $totalUsed,
                        'expired_points' => $totalExpired,
                        'vip_level' => $this->calculateVipLevel($totalEarned),
                    ]);
                } else {
                    $point->update([
                        'total_points' => max(0, $totalPoints),
                        'earned_points' => $totalEarned,
                        'used_points' => $totalUsed,
                        'expired_points' => $totalExpired,
                        'vip_level' => $this->calculateVipLevel($totalEarned),
                    ]);
                }

                $fixedCount++;
                $this->line("Đã sửa điểm cho user: {$user->name} - Tích: {$totalEarned}, Dùng: {$totalUsed}, Còn: {$totalPoints}");
            }

            DB::commit();

            $this->info("Hoàn thành! Đã sửa điểm cho {$fixedCount} users.");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Lỗi: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    private function calculateVipLevel(int $earnedPoints): string
    {
        if ($earnedPoints >= 7000000) return 'Diamond'; // 7,000,000 điểm
        if ($earnedPoints >= 4900000) return 'Platinum'; // 4,900,000 điểm
        if ($earnedPoints >= 4300000) return 'Gold'; // 4,300,000 điểm
        if ($earnedPoints >= 3400000) return 'Silver'; // 3,400,000 điểm
        return 'Bronze';
    }
}
