<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupons = [
            // Mã giảm giá cho khách hàng mới
            [
                'code' => 'WELCOME10',
                'discount_type' => 'percentage',
                'discount_value' => 10,
                'min_order_value' => 2000000, // Từ 2 triệu
                'max_discount_value' => 500000, // Tối đa 500k
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->addMonths(6),
                'usage_limit' => 200,
                'usage_limit_per_user' => 1,
                'status' => 1,
            ],
            
            // Mã giảm giá cố định cho điện thoại tầm trung
            [
                'code' => 'PHONE100K',
                'discount_type' => 'fixed',
                'discount_value' => 100000,
                'min_order_value' => 3000000, // Từ 3 triệu
                'max_discount_value' => null,
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->addMonths(3),
                'usage_limit' => 100,
                'usage_limit_per_user' => 1,
                'status' => 1,
            ],
            
            // Mã giảm giá flash sale
            [
                'code' => 'FLASH15',
                'discount_type' => 'percentage',
                'discount_value' => 15,
                'min_order_value' => 5000000, // Từ 5 triệu
                'max_discount_value' => 1000000, // Tối đa 1 triệu
                'start_date' => Carbon::now()->subDays(7),
                'end_date' => Carbon::now()->addDays(7),
                'usage_limit' => 50,
                'usage_limit_per_user' => 1,
                'status' => 1,
            ],
            
            // Mã giảm giá cho điện thoại cao cấp
            [
                'code' => 'PREMIUM20',
                'discount_type' => 'percentage',
                'discount_value' => 20,
                'min_order_value' => 10000000, // Từ 10 triệu
                'max_discount_value' => 2000000, // Tối đa 2 triệu
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->addMonths(12),
                'usage_limit' => 30,
                'usage_limit_per_user' => 1,
                'status' => 1,
            ],
            
            // Mã giảm giá cho khách hàng thân thiết
            [
                'code' => 'VIP200K',
                'discount_type' => 'fixed',
                'discount_value' => 200000,
                'min_order_value' => 4000000, // Từ 4 triệu
                'max_discount_value' => null,
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->addMonths(12),
                'usage_limit' => 80,
                'usage_limit_per_user' => 1,
                'status' => 1,
            ],
            
            // Mã giảm giá cho đơn hàng lớn
            [
                'code' => 'BIGORDER25',
                'discount_type' => 'percentage',
                'discount_value' => 25,
                'min_order_value' => 15000000, // Từ 15 triệu
                'max_discount_value' => 3000000, // Tối đa 3 triệu
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->addMonths(6),
                'usage_limit' => 20,
                'usage_limit_per_user' => 1,
                'status' => 1,
            ],
            
            // Mã giảm giá cho điện thoại tầm thấp
            [
                'code' => 'BUDGET50K',
                'discount_type' => 'fixed',
                'discount_value' => 50000,
                'min_order_value' => 1500000, // Từ 1.5 triệu
                'max_discount_value' => null,
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->addMonths(6),
                'usage_limit' => 150,
                'usage_limit_per_user' => 1,
                'status' => 1,
            ],
            
            // Mã giảm giá cho khách hàng cũ
            [
                'code' => 'LOYALTY12',
                'discount_type' => 'percentage',
                'discount_value' => 12,
                'min_order_value' => 2500000, // Từ 2.5 triệu
                'max_discount_value' => 600000, // Tối đa 600k
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->addMonths(12),
                'usage_limit' => 120,
                'usage_limit_per_user' => 1,
                'status' => 1,
            ],
            
            // Mã giảm giá cho điện thoại gaming
            [
                'code' => 'GAMING150K',
                'discount_type' => 'fixed',
                'discount_value' => 150000,
                'min_order_value' => 6000000, // Từ 6 triệu
                'max_discount_value' => null,
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->addMonths(6),
                'usage_limit' => 60,
                'usage_limit_per_user' => 1,
                'status' => 1,
            ],
            
            // Mã giảm giá cho điện thoại camera cao cấp
            [
                'code' => 'CAMERA18',
                'discount_type' => 'percentage',
                'discount_value' => 18,
                'min_order_value' => 8000000, // Từ 8 triệu
                'max_discount_value' => 1500000, // Tối đa 1.5 triệu
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->addMonths(9),
                'usage_limit' => 40,
                'usage_limit_per_user' => 1,
                'status' => 1,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::create($coupon);
        }
        
        $this->command->info('Phone coupons seeded successfully!');
    }
} 