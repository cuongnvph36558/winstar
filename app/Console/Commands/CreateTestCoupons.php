<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Coupon;
use Carbon\Carbon;

class CreateTestCoupons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coupons:create-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create test coupons for development';

    /**
     * Execute the console command.
     */
    public function handle()
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

        foreach ($coupons as $couponData) {
            $existing = Coupon::where('code', $couponData['code'])->first();
            if (!$existing) {
                Coupon::create($couponData);
                $this->info("Created coupon: {$couponData['code']}");
            } else {
                $this->warn("Coupon {$couponData['code']} already exists");
            }
        }

        $this->info('Test coupons created successfully!');
        $this->info('Available test codes: WELCOME10, SAVE50K, FLASH20, NEWUSER');
    }
} 