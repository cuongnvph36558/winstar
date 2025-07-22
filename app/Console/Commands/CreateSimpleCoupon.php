<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Coupon;
use Carbon\Carbon;

class CreateSimpleCoupon extends Command
{
    protected $signature = 'coupon:create {code}';
    protected $description = 'Create a simple test coupon';

    public function handle()
    {
        $code = $this->argument('code');
        
        $coupon = Coupon::create([
            'code' => $code,
            'discount_type' => 'fixed',
            'discount_value' => 50000,
            'min_order_value' => 100000,
            'max_discount_value' => null,
            'start_date' => Carbon::now()->subDays(1),
            'end_date' => Carbon::now()->addMonths(1),
            'usage_limit' => 100,
            'usage_limit_per_user' => 1,
            'status' => 1,
        ]);

        $this->info("Created coupon: {$code} - Giảm 50,000đ cho đơn hàng từ 100,000đ");
    }
} 