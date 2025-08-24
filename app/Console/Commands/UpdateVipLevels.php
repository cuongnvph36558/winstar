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
        if ($earnedPoints >= 700000) return 'Diamond'; // Tăng từ 600k lên 700k (+100k)
        if ($earnedPoints >= 490000) return 'Platinum'; // Tăng từ 390k lên 490k (+100k)
        if ($earnedPoints >= 430000) return 'Gold'; // Tăng từ 330k lên 430k (+100k)
        if ($earnedPoints >= 340000) return 'Silver'; // Tăng từ 240k lên 340k (+100k)
        return 'Bronze';
    }
}
