<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        $colors = [
            // Màu cơ bản
            ['name' => 'Đen', 'color_code' => '#000000'],
            ['name' => 'Trắng', 'color_code' => '#FFFFFF'],
            ['name' => 'Xám', 'color_code' => '#808080'],
            ['name' => 'Bạc', 'color_code' => '#C0C0C0'],
            ['name' => 'Vàng', 'color_code' => '#FFD700'],
            ['name' => 'Hồng', 'color_code' => '#FFC0CB'],
            ['name' => 'Đỏ', 'color_code' => '#FF0000'],
            ['name' => 'Cam', 'color_code' => '#FFA500'],
            ['name' => 'Tím', 'color_code' => '#800080'],
            ['name' => 'Xanh lá', 'color_code' => '#00FF00'],
            ['name' => 'Xanh dương', 'color_code' => '#0000FF'],
            ['name' => 'Xanh ngọc', 'color_code' => '#40E0D0'],
            ['name' => 'Nâu', 'color_code' => '#8B4513'],
            ['name' => 'Be', 'color_code' => '#F5F5DC'],
            ['name' => 'Kem', 'color_code' => '#FFFDD0'],
            ['name' => 'Xanh navy', 'color_code' => '#000080'],
            ['name' => 'Xanh rêu', 'color_code' => '#4B5320'],
            ['name' => 'Tím pastel', 'color_code' => '#D8BFD8'],
            ['name' => 'Xanh pastel', 'color_code' => '#B2DFDB'],
            ['name' => 'Hồng pastel', 'color_code' => '#FFD1DC'],

            // Màu riêng của iPhone
            ['name' => 'Xám không gian', 'color_code' => '#2C2C2C'],
            ['name' => 'Trắng ánh sao', 'color_code' => '#F5F2EB'],
            ['name' => 'Đen bóng đêm', 'color_code' => '#1E1E2F'],
            ['name' => 'Xanh biển', 'color_code' => '#4B9CD3'],
            ['name' => 'Cam san hô', 'color_code' => '#FF6F61'],
            ['name' => 'Xanh dương nhạt', 'color_code' => '#ADD8E6'],
            ['name' => 'Xanh lá nhạt', 'color_code' => '#C8E6C9'],
            ['name' => 'Xanh rêu', 'color_code' => '#9BB7D4'],
            ['name' => 'Tím đậm', 'color_code' => '#5A5366'],
            ['name' => 'Titan tự nhiên', 'color_code' => '#D6D6D6'],
            ['name' => 'Titan trắng', 'color_code' => '#F8F8F8'],
            ['name' => 'Titan đen', 'color_code' => '#1A1A1A'],
            ['name' => 'Titan xanh', 'color_code' => '#4D6A78'],
            ['name' => 'Vàng nhạt', 'color_code' => '#FFFACD'],
            ['name' => 'Xanh nhạt', 'color_code' => '#CFE8F3'],
            ['name' => 'Xanh navy đậm', 'color_code' => '#001F3F'],
            ['name' => 'Đỏ sản phẩm', 'color_code' => '#BE0032'],
            ['name' => 'Than chì', 'color_code' => '#3B3B4C'],
        ];

        foreach ($colors as $color) {
            Color::updateOrCreate(
                ['name' => $color['name']],
                $color
            );
        }
    }
}
