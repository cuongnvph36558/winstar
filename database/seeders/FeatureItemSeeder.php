<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FeatureItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('feature_items')->insert([
            [
                'feature_id' => 1,
                'icon' => 'icon-strategy',
                'title' => 'Chất lượng cao',
                'description' => 'Sản phẩm được tuyển chọn kỹ lưỡng với chất lượng đảm bảo và giá cả hợp lý.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'feature_id' => 1,
                'icon' => 'icon-tools-2',
                'title' => 'Giao hàng nhanh',
                'description' => 'Giao hàng toàn quốc với thời gian nhanh chóng và an toàn.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'feature_id' => 1,
                'icon' => 'icon-mobile',
                'title' => 'Thanh toán đa dạng',
                'description' => 'Hỗ trợ nhiều hình thức thanh toán tiện lợi và bảo mật.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'feature_id' => 1,
                'icon' => 'icon-lifesaver',
                'title' => 'Hỗ trợ 24/7',
                'description' => 'Đội ngũ chăm sóc khách hàng tận tình, hỗ trợ 24/7.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
} 