<?php

namespace Database\Seeders;

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
            // ========== BRONZE LEVEL (1% tích điểm) ==========
            [
                'name' => 'Mã Giảm Giá Bronze - 1%',
                'code' => 'BRONZE1',
                'description' => 'Giảm giá 1% cho khách hàng Bronze',
                'discount_type' => 'percentage',
                'discount_value' => 1,
                'min_order_value' => 5000000, // 5 triệu
                'max_discount' => 100000,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(6),
                'validity_days' => 30,
                'exchange_points' => 50000,
                'vip_level' => 'Bronze',
                'status' => 1,
                'usage_limit' => 1000,
                'used_count' => 0,
            ],
            [
                'name' => 'Mã Giảm Giá Bronze - 2%',
                'code' => 'BRONZE2',
                'description' => 'Giảm giá 2% cho khách hàng Bronze',
                'discount_type' => 'percentage',
                'discount_value' => 2,
                'min_order_value' => 10000000, // 10 triệu
                'max_discount' => 200000,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(6),
                'validity_days' => 30,
                'exchange_points' => 100000,
                'vip_level' => 'Bronze',
                'status' => 1,
                'usage_limit' => 800,
                'used_count' => 0,
            ],

            // ========== SILVER LEVEL (2% tích điểm) ==========
            [
                'name' => 'Mã Giảm Giá Silver - 2%',
                'code' => 'SILVER2',
                'description' => 'Giảm giá 2% cho khách hàng Silver',
                'discount_type' => 'percentage',
                'discount_value' => 2,
                'min_order_value' => 5000000,
                'max_discount' => 200000,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(6),
                'validity_days' => 30,
                'exchange_points' => 80000,
                'vip_level' => 'Silver',
                'status' => 1,
                'usage_limit' => 600,
                'used_count' => 0,
            ],
            [
                'name' => 'Mã Giảm Giá Silver - 3%',
                'code' => 'SILVER3',
                'description' => 'Giảm giá 3% cho khách hàng Silver',
                'discount_type' => 'percentage',
                'discount_value' => 3,
                'min_order_value' => 10000000,
                'max_discount' => 300000,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(6),
                'validity_days' => 30,
                'exchange_points' => 150000,
                'vip_level' => 'Silver',
                'status' => 1,
                'usage_limit' => 500,
                'used_count' => 0,
            ],

            // ========== GOLD LEVEL (3% tích điểm) ==========
            [
                'name' => 'Mã Giảm Giá Gold - 3%',
                'code' => 'GOLD3',
                'description' => 'Giảm giá 3% cho khách hàng Gold',
                'discount_type' => 'percentage',
                'discount_value' => 3,
                'min_order_value' => 5000000,
                'max_discount' => 300000,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(6),
                'validity_days' => 30,
                'exchange_points' => 120000,
                'vip_level' => 'Gold',
                'status' => 1,
                'usage_limit' => 400,
                'used_count' => 0,
            ],
            [
                'name' => 'Mã Giảm Giá Gold - 4%',
                'code' => 'GOLD4',
                'description' => 'Giảm giá 4% cho khách hàng Gold',
                'discount_type' => 'percentage',
                'discount_value' => 4,
                'min_order_value' => 10000000,
                'max_discount' => 400000,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(6),
                'validity_days' => 30,
                'exchange_points' => 200000,
                'vip_level' => 'Gold',
                'status' => 1,
                'usage_limit' => 300,
                'used_count' => 0,
            ],

            // ========== PLATINUM LEVEL (4% tích điểm) ==========
            [
                'name' => 'Mã Giảm Giá Platinum - 4%',
                'code' => 'PLATINUM4',
                'description' => 'Giảm giá 4% cho khách hàng Platinum',
                'discount_type' => 'percentage',
                'discount_value' => 4,
                'min_order_value' => 5000000,
                'max_discount' => 400000,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(6),
                'validity_days' => 30,
                'exchange_points' => 160000,
                'vip_level' => 'Platinum',
                'status' => 1,
                'usage_limit' => 250,
                'used_count' => 0,
            ],
            [
                'name' => 'Mã Giảm Giá Platinum - 5%',
                'code' => 'PLATINUM5',
                'description' => 'Giảm giá 5% cho khách hàng Platinum',
                'discount_type' => 'percentage',
                'discount_value' => 5,
                'min_order_value' => 10000000,
                'max_discount' => 500000,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(6),
                'validity_days' => 30,
                'exchange_points' => 300000,
                'vip_level' => 'Platinum',
                'status' => 1,
                'usage_limit' => 200,
                'used_count' => 0,
            ],

            // ========== DIAMOND LEVEL (5% tích điểm) ==========
            [
                'name' => 'Mã Giảm Giá Diamond - 5%',
                'code' => 'DIAMOND5',
                'description' => 'Giảm giá 5% cho khách hàng Diamond',
                'discount_type' => 'percentage',
                'discount_value' => 5,
                'min_order_value' => 5000000,
                'max_discount' => 500000,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(6),
                'validity_days' => 30,
                'exchange_points' => 200000,
                'vip_level' => 'Diamond',
                'status' => 1,
                'usage_limit' => 150,
                'used_count' => 0,
            ],
            [
                'name' => 'Mã Giảm Giá Diamond - 6%',
                'code' => 'DIAMOND6',
                'description' => 'Giảm giá 6% cho khách hàng Diamond',
                'discount_type' => 'percentage',
                'discount_value' => 6,
                'min_order_value' => 10000000,
                'max_discount' => 600000,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(6),
                'validity_days' => 30,
                'exchange_points' => 400000,
                'vip_level' => 'Diamond',
                'status' => 1,
                'usage_limit' => 100,
                'used_count' => 0,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::create($coupon);
        }
    }
} 