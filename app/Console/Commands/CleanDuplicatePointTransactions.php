<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PointTransaction;
use Illuminate\Support\Facades\DB;

class CleanDuplicatePointTransactions extends Command
{
    protected $signature = 'points:clean-duplicates';
    protected $description = 'Dọn dẹp các giao dịch điểm trùng lặp';

    public function handle()
    {
        $this->info('Bắt đầu dọn dẹp giao dịch điểm trùng lặp...');

        try {
            DB::beginTransaction();

            // Tìm các giao dịch trùng lặp
            $duplicates = DB::table('point_transactions')
                ->select('user_id', 'reference_type', 'reference_id', 'type', 'description', DB::raw('COUNT(*) as count'))
                ->groupBy('user_id', 'reference_type', 'reference_id', 'type', 'description')
                ->having('count', '>', 1)
                ->get();

            $totalRemoved = 0;

            foreach ($duplicates as $duplicate) {
                $this->info("Tìm thấy {$duplicate->count} giao dịch trùng lặp cho đơn hàng #{$duplicate->reference_id}");

                // Lấy tất cả giao dịch trùng lặp
                $transactions = PointTransaction::where('user_id', $duplicate->user_id)
                    ->where('reference_type', $duplicate->reference_type)
                    ->where('reference_id', $duplicate->reference_id)
                    ->where('type', $duplicate->type)
                    ->where('description', $duplicate->description)
                    ->orderBy('created_at', 'asc')
                    ->get();

                // Giữ lại giao dịch đầu tiên, xóa các giao dịch còn lại
                $firstTransaction = $transactions->first();
                $transactionsToDelete = $transactions->slice(1);

                foreach ($transactionsToDelete as $transaction) {
                    $transaction->delete();
                    $totalRemoved++;
                }

                $this->info("Đã xóa " . $transactionsToDelete->count() . " giao dịch trùng lặp");
            }

            // Cập nhật lại tổng điểm cho các user bị ảnh hưởng
            $affectedUsers = DB::table('point_transactions')
                ->select('user_id')
                ->groupBy('user_id', 'reference_type', 'reference_id', 'type', 'description')
                ->having(DB::raw('COUNT(*)'), '>', 1)
                ->pluck('user_id')
                ->unique();

            foreach ($affectedUsers as $userId) {
                $this->updateUserPoints($userId);
            }

            DB::commit();

            $this->info("Hoàn thành! Đã xóa {$totalRemoved} giao dịch trùng lặp.");
            $this->info("Đã cập nhật lại điểm cho " . $affectedUsers->count() . " user.");

            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Lỗi: ' . $e->getMessage());
            return 1;
        }
    }

    private function updateUserPoints($userId)
    {
        // Tính lại tổng điểm từ các giao dịch
        $totalEarned = PointTransaction::where('user_id', $userId)
            ->where('type', 'earn')
            ->where('is_expired', false)
            ->sum('points');

        $totalUsed = PointTransaction::where('user_id', $userId)
            ->where('type', 'use')
            ->sum(DB::raw('ABS(points)'));

        $totalExpired = PointTransaction::where('user_id', $userId)
            ->where('type', 'earn')
            ->where('is_expired', true)
            ->sum('points');

        $totalPoints = $totalEarned - $totalUsed;

        // Cập nhật điểm trong bảng points
        DB::table('points')
            ->where('user_id', $userId)
            ->update([
                'total_points' => max(0, $totalPoints),
                'earned_points' => $totalEarned,
                'used_points' => $totalUsed,
                'expired_points' => $totalExpired,
            ]);

        $this->line("Đã cập nhật điểm cho user ID: {$userId}");
    }
} 