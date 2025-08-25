<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\PointTransaction;
use App\Services\PointService;
use Illuminate\Support\Facades\DB;

class FixUserPoints extends Command
{
    protected $signature = 'points:fix-all-users';
    protected $description = 'Sửa lại điểm cho tất cả users (bao gồm cả transaction bonus)';

    public function handle()
    {
        $this->info('Bắt đầu sửa điểm cho tất cả users...');

        $users = User::all();
        $fixedCount = 0;
        $errorCount = 0;

        foreach ($users as $user) {
            try {
                $this->fixUserPoints($user);
                $fixedCount++;
                $this->line("✅ Đã sửa điểm cho user: {$user->name} (ID: {$user->id})");
            } catch (\Exception $e) {
                $errorCount++;
                $this->error("❌ Lỗi sửa điểm cho user {$user->name}: " . $e->getMessage());
            }
        }

        $this->info("\n=== KẾT QUẢ ===");
        $this->info("✅ Đã sửa thành công: {$fixedCount} users");
        $this->info("❌ Lỗi: {$errorCount} users");
        $this->info("Tổng cộng: " . ($fixedCount + $errorCount) . " users");

        return 0;
    }

    private function fixUserPoints(User $user): void
    {
        // Lấy hoặc tạo point record
        $point = $user->point;
        if (!$point) {
            $point = $user->point()->create([
                'total_points' => 0,
                'earned_points' => 0,
                'used_points' => 0,
                'expired_points' => 0,
            ]);
        }

        // Tính toán lại điểm từ transactions (bao gồm cả earn và bonus)
        $totalEarned = PointTransaction::where('user_id', $user->id)
            ->whereIn('type', ['earn', 'bonus'])
            ->where('is_expired', false)
            ->sum('points');

        $totalUsed = PointTransaction::where('user_id', $user->id)
            ->where('type', 'use')
            ->sum(DB::raw('ABS(points)'));

        $totalExpired = PointTransaction::where('user_id', $user->id)
            ->whereIn('type', ['earn', 'bonus'])
            ->where('is_expired', true)
            ->sum('points');

        $calculatedTotal = $totalEarned - $totalUsed;

        // Cập nhật point record
        $point->update([
            'total_points' => max(0, $calculatedTotal),
            'earned_points' => $totalEarned,
            'used_points' => $totalUsed,
            'expired_points' => $totalExpired,
        ]);

        // Clear cache
        \Cache::forget('user_points_' . $user->id);
        \Cache::forget('admin_user_points_' . $user->id);
        \Cache::forget('user_point_stats_' . $user->id);
        \Cache::forget('user_point_history_' . $user->id);
    }
}
