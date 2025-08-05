<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy tất cả users
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->info('Không có user nào để tạo dữ liệu điểm danh.');
            return;
        }

        // Tạo dữ liệu điểm danh cho 30 ngày gần đây
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        foreach ($users as $user) {
            $currentDate = $startDate->copy();
            
            while ($currentDate <= $endDate) {
                // Bỏ qua cuối tuần (thứ 7, chủ nhật)
                if ($currentDate->isWeekend()) {
                    $currentDate->addDay();
                    continue;
                }

                // Tạo điểm danh với xác suất 80%
                if (rand(1, 100) <= 80) {
                    $checkInTime = $currentDate->copy()->setTime(rand(7, 9), rand(0, 59));
                    $checkOutTime = $currentDate->copy()->setTime(rand(17, 19), rand(0, 59));
                    
                    // Tính điểm tích được
                    $workHours = $checkInTime->diffInHours($checkOutTime);
                    $basePoints = $workHours * 10;
                    $bonusPoints = 0;
                    
                    // Bonus điểm danh sớm
                    if ($checkInTime->hour < 9) {
                        $bonusPoints += 5;
                    }
                    
                    // Bonus làm việc lâu
                    if ($workHours >= 8) {
                        $bonusPoints += 10;
                    } elseif ($workHours >= 6) {
                        $bonusPoints += 5;
                    }
                    
                    $totalPoints = $basePoints + $bonusPoints;

                    Attendance::create([
                        'user_id' => $user->id,
                        'date' => $currentDate->toDateString(),
                        'check_in_time' => $checkInTime,
                        'check_out_time' => $checkOutTime,
                        'points_earned' => $totalPoints,
                        'status' => 'present',
                        'notes' => 'Dữ liệu mẫu'
                    ]);
                }

                $currentDate->addDay();
            }
        }

        $this->command->info('Đã tạo dữ liệu điểm danh mẫu thành công!');
    }
}
