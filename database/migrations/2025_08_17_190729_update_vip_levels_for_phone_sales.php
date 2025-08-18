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
     * Tính toán VIP level mới dựa trên điểm đã tích
     */
    private function calculateNewVipLevel(int $earnedPoints): string
    {
        if ($earnedPoints >= 50000) return 'Diamond';
        if ($earnedPoints >= 25000) return 'Platinum';
        if ($earnedPoints >= 10000) return 'Gold';
        if ($earnedPoints >= 5000) return 'Silver';
        return 'Bronze';
    }
};
