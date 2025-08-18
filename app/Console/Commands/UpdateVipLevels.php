<?php

namespace App\Console\Commands;

use App\Models\Point;
use Illuminate\Console\Command;

class UpdateVipLevels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'points:update-vip-levels';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update VIP levels for all users based on their earned points';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating VIP levels for all users...');

        $points = Point::all();
        $updated = 0;

        foreach ($points as $point) {
            $oldLevel = $point->vip_level;
            $newLevel = $this->calculateVipLevel($point->earned_points);

            if ($oldLevel !== $newLevel) {
                $point->update(['vip_level' => $newLevel]);
                $updated++;
                $this->line("Updated user {$point->user->name}: {$oldLevel} → {$newLevel}");
            }
        }

        $this->info("Updated {$updated} users out of {$points->count()} total users.");
    }

    private function calculateVipLevel(int $earnedPoints): string
    {
        if ($earnedPoints >= 600000) return 'Diamond'; // 30 đơn × 20,000 điểm
        if ($earnedPoints >= 390000) return 'Platinum'; // 30 đơn × 13,000 điểm
        if ($earnedPoints >= 330000) return 'Gold'; // 30 đơn × 11,000 điểm
        if ($earnedPoints >= 240000) return 'Silver'; // 30 đơn × 8,000 điểm
        return 'Bronze';
    }
}
