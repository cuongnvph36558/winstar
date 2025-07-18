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
            [
                'code' => 'WELCOME10',
                'discount_type' => 'percentage',
                'discount_value' => 10,
                'min_order_value' => 500000,
                'max_discount_value' => 100000,
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->addMonths(6),
                'usage_limit' => 100,
                'usage_limit_per_user' => 1,
                'status' => 1,
            ],
            [
                'code' => 'SAVE50K',
                'discount_type' => 'fixed',
                'discount_value' => 50000,
                'min_order_value' => 1000000,
                'max_discount_value' => null,
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->addMonths(3),
                'usage_limit' => 50,
                'usage_limit_per_user' => 1,
                'status' => 1,
            ],
            [
                'code' => 'FLASH20',
                'discount_type' => 'percentage',
                'discount_value' => 20,
                'min_order_value' => 2000000,
                'max_discount_value' => 200000,
                'start_date' => Carbon::now()->subDays(7),
                'end_date' => Carbon::now()->addDays(7),
                'usage_limit' => 20,
                'usage_limit_per_user' => 1,
                'status' => 1,
            ],
            [
                'code' => 'NEWUSER',
                'discount_type' => 'fixed',
                'discount_value' => 100000,
                'min_order_value' => 500000,
                'max_discount_value' => null,
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->addMonths(12),
                'usage_limit' => 200,
                'usage_limit_per_user' => 1,
                'status' => 1,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::create($coupon);
        }
    }
} 