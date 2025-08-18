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
     * Tính toán VIP level mới dựa trên điểm đã tích (300 triệu VND lên cấp)
     */
    private function calculateNewVipLevel(int $earnedPoints): string
    {
        if ($earnedPoints >= 600000) return 'Diamond'; // 30 đơn × 20,000 điểm
        if ($earnedPoints >= 390000) return 'Platinum'; // 30 đơn × 13,000 điểm
        if ($earnedPoints >= 330000) return 'Gold'; // 30 đơn × 11,000 điểm
        if ($earnedPoints >= 240000) return 'Silver'; // 30 đơn × 8,000 điểm
        return 'Bronze';
    }
};
