<?php

namespace Database\Seeders;

use App\Models\PointVoucher;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PointVoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vouchers = [
            [
                'name' => 'Giảm 50.000đ cho đơn hàng từ 500.000đ',
                'description' => 'Voucher giảm giá 50.000đ cho đơn hàng có giá trị từ 500.000đ trở lên',
                'points_required' => 100,
                'discount_value' => 50000,
                'discount_type' => 'fixed',
                'min_order_value' => 500000,
                'max_usage' => 1000,
                'current_usage' => 0,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(6),
                'is_active' => true,
            ],
            [
                'name' => 'Giảm 10% cho đơn hàng từ 1.000.000đ',
                'description' => 'Voucher giảm giá 10% cho đơn hàng có giá trị từ 1.000.000đ trở lên',
                'points_required' => 200,
                'discount_value' => 10,
                'discount_type' => 'percentage',
                'min_order_value' => 1000000,
                'max_usage' => 500,
                'current_usage' => 0,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(6),
                'is_active' => true,
            ],
            [
                'name' => 'Giảm 100.000đ cho đơn hàng từ 2.000.000đ',
                'description' => 'Voucher giảm giá 100.000đ cho đơn hàng có giá trị từ 2.000.000đ trở lên',
                'points_required' => 300,
                'discount_value' => 100000,
                'discount_type' => 'fixed',
                'min_order_value' => 2000000,
                'max_usage' => 200,
                'current_usage' => 0,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(6),
                'is_active' => true,
            ],
            [
                'name' => 'Giảm 15% cho đơn hàng từ 3.000.000đ',
                'description' => 'Voucher giảm giá 15% cho đơn hàng có giá trị từ 3.000.000đ trở lên',
                'points_required' => 500,
                'discount_value' => 15,
                'discount_type' => 'percentage',
                'min_order_value' => 3000000,
                'max_usage' => 100,
                'current_usage' => 0,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(6),
                'is_active' => true,
            ],
            [
                'name' => 'Giảm 200.000đ cho đơn hàng từ 5.000.000đ',
                'description' => 'Voucher giảm giá 200.000đ cho đơn hàng có giá trị từ 5.000.000đ trở lên',
                'points_required' => 800,
                'discount_value' => 200000,
                'discount_type' => 'fixed',
                'min_order_value' => 5000000,
                'max_usage' => 50,
                'current_usage' => 0,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(6),
                'is_active' => true,
            ],
        ];

        foreach ($vouchers as $voucher) {
            PointVoucher::create($voucher);
        }

        $this->command->info('Point vouchers seeded successfully!');
    }
}
