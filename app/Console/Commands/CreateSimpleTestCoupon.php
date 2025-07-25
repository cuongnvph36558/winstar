<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Coupon;
use Carbon\Carbon;

class CreateSimpleTestCoupon extends Command
{
    protected $signature = 'coupon:simple {code}';
    protected $description = 'Create a simple test coupon';

    public function handle()
    {
        $code = $this->argument('code');
        
        try {
            // Check if coupon already exists
            $existing = Coupon::where('code', $code)->first();
            if ($existing) {
                $this->warn("Coupon {$code} already exists!");
                return;
            }

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

            $this->info("✅ Created coupon: {$code}");
            $this->info("   - Giảm: 50,000đ");
            $this->info("   - Đơn hàng tối thiểu: 100,000đ");
            $this->info("   - Hiệu lực: " . $coupon->start_date->format('d/m/Y') . " - " . $coupon->end_date->format('d/m/Y'));
            
        } catch (\Exception $e) {
            $this->error("❌ Error creating coupon: " . $e->getMessage());
        }
    }
} 