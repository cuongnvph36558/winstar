<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CouponUser;
use App\Models\Coupon;
use App\Models\User;

class CouponUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy danh sách users và coupons có sẵn
        $users = User::take(5)->get();
        $coupons = Coupon::take(3)->get();

        if ($users->isEmpty() || $coupons->isEmpty()) {
            $this->command->info('Không có đủ users hoặc coupons để tạo dữ liệu mẫu.');
            return;
        }

        $couponUsers = [];

        // Tạo dữ liệu mẫu cho coupon_users
        foreach ($users as $user) {
            foreach ($coupons as $coupon) {
                // Tạo 1-2 records cho mỗi user-coupon combination
                $count = rand(1, 2);
                
                for ($i = 0; $i < $count; $i++) {
                    $couponUsers[] = [
                        'user_id' => $user->id,
                        'coupon_id' => $coupon->id,
                        'created_at' => now()->subDays(rand(1, 30)),
                        'updated_at' => now()->subDays(rand(1, 30)),
                    ];
                }
            }
        }

        // Insert dữ liệu
        foreach ($couponUsers as $couponUser) {
            CouponUser::create($couponUser);
        }

        $this->command->info('Đã tạo ' . count($couponUsers) . ' records cho bảng coupon_users.');
    }
} 