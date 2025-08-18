<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cập nhật VIP levels cho tất cả users dựa trên điểm đã tích
        $points = DB::table('points')->get();
        
        foreach ($points as $point) {
            $newVipLevel = $this->calculateNewVipLevel($point->earned_points);
            
            DB::table('points')
                ->where('id', $point->id)
                ->update(['vip_level' => $newVipLevel]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Không cần rollback vì đây là cập nhật logic nghiệp vụ
    }

    /**
     * Tính toán VIP level mới dựa trên điểm đã tích (tăng lên 80 lần)
     */
    private function calculateNewVipLevel(int $earnedPoints): string
    {
        if ($earnedPoints >= 4000000) return 'Diamond'; // 50,000 * 80
        if ($earnedPoints >= 2000000) return 'Platinum'; // 25,000 * 80
        if ($earnedPoints >= 800000) return 'Gold'; // 10,000 * 80
        if ($earnedPoints >= 400000) return 'Silver'; // 5,000 * 80
        return 'Bronze';
    }
};
