<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('features')->insert([
            'title' => 'Tại sao chọn chúng tôi',
            'subtitle' => 'Cam kết mang đến trải nghiệm mua sắm tốt nhất cho khách hàng',
            'image' => 'client/assets/images/promo.png',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
} 