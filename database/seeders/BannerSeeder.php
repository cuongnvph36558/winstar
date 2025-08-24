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
            ],
            // New banners from storage/app/public/banners folder
            [
                'title' => 'Banner Quảng cáo 1',
                'image_url' => 'banners/NLIdI0CEZn3D8TDa0W1jTlRHLNq8kRubdsTfkXoc.jpg',
                'link' => '#',
                'start_date' => now(),
                'end_date' => now()->addMonths(6),
                'status' => '1'
            ],
            [
                'title' => 'Banner Quảng cáo 2',
                'image_url' => 'banners/YK9u0jW9jSkizaDr4N0WjaPVF8ofxactliD0sCfF.webp',
                'link' => '#',
                'start_date' => now(),
                'end_date' => now()->addMonths(6),
                'status' => '1'
            ],
            [
                'title' => 'Banner Quảng cáo 3',
                'image_url' => 'banners/HsL6HaaE9O2pEnGtPVSnLo0L7PtDHtzM4ir5V6L5.jpg',
                'link' => '#',
                'start_date' => now(),
                'end_date' => now()->addMonths(6),
                'status' => '1'
            ],
            [
                'title' => 'Banner Quảng cáo 4',
                'image_url' => 'banners/o0cRAlsOh78vT3tOASnRfyxfYjOUxs16Rucp8Txg.webp',
                'link' => '#',
                'start_date' => now(),
                'end_date' => now()->addMonths(6),
                'status' => '1'
            ],
            [
                'title' => 'Banner Quảng cáo 5',
                'image_url' => 'banners/GucsvFiGlj0CfDJA9Bfv7Wxv9Y24pINeyCHNGmd1.webp',
                'link' => '#',
                'start_date' => now(),
                'end_date' => now()->addMonths(6),
                'status' => '1'
            ],
            [
                'title' => 'Banner Quảng cáo 6',
                'image_url' => 'banners/2E5wO5Urz5yh1TWSoAVYwjp4YRr8JjHmNHCpOdt9.webp',
                'link' => '#',
                'start_date' => now(),
                'end_date' => now()->addMonths(6),
                'status' => '1'
            ],
            [
                'title' => 'Banner Quảng cáo 7',
                'image_url' => 'banners/kUEBHljBObcc5io1U3FIkAXdY4UDeit26FcHWO4b.webp',
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