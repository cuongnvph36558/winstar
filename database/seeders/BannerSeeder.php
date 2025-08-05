<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'title' => 'iPhone 15 Pro Max - Đẳng cấp mới',
                'image_url' => 'banners/banner-1-iphone.jpg',
                'link' => '#',
                'start_date' => now(),
                'end_date' => now()->addMonths(6),
                'status' => '1'
            ],
            [
                'title' => 'Samsung Galaxy S24 Ultra - Sáng tạo vô tận',
                'image_url' => 'banners/banner-2-samsung.jpg',
                'link' => '#',
                'start_date' => now(),
                'end_date' => now()->addMonths(6),
                'status' => '1'
            ],
            [
                'title' => 'Xiaomi 14 Ultra - Hiệu năng đỉnh cao',
                'image_url' => 'banners/banner-3-xiaomi.jpg',
                'link' => '#',
                'start_date' => now(),
                'end_date' => now()->addMonths(6),
                'status' => '1'
            ],
            [
                'title' => 'OPPO Find X7 Ultra - Nghệ thuật nhiếp ảnh',
                'image_url' => 'banners/banner-4-oppo.jpg',
                'link' => '#',
                'start_date' => now(),
                'end_date' => now()->addMonths(6),
                'status' => '1'
            ]
        ];

        foreach ($banners as $banner) {
            Banner::updateOrCreate(
                ['image_url' => $banner['image_url']],
                $banner
            );
        }

        $this->command->info('Banner data seeded successfully!');
    }
} 