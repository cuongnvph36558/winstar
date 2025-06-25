<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        $colors = [
            ['name' => 'Đỏ', 'color_code' => '#FF0000'],
            ['name' => 'Đỏ tươi', 'color_code' => '#FF1493'],
            ['name' => 'Đỏ đậm', 'color_code' => '#8B0000'],
            ['name' => 'Xanh lá', 'color_code' => '#00FF00'],
            ['name' => 'Xanh lá đậm', 'color_code' => '#006400'],
            ['name' => 'Xanh lá nhạt', 'color_code' => '#90EE90'],
            ['name' => 'Xanh dương', 'color_code' => '#0000FF'],
            ['name' => 'Xanh dương nhạt', 'color_code' => '#87CEEB'],
            ['name' => 'Xanh dương đậm', 'color_code' => '#000080'],
            ['name' => 'Vàng', 'color_code' => '#FFFF00'],
            ['name' => 'Vàng nhạt', 'color_code' => '#FFFFE0'],
            ['name' => 'Vàng đậm', 'color_code' => '#DAA520'],
            ['name' => 'Tím', 'color_code' => '#800080'],
            ['name' => 'Tím nhạt', 'color_code' => '#E6E6FA'],
            ['name' => 'Tím đậm', 'color_code' => '#4B0082'],
            ['name' => 'Hồng', 'color_code' => '#FFC0CB'],
            ['name' => 'Hồng đậm', 'color_code' => '#FF69B4'],
            ['name' => 'Hồng nhạt', 'color_code' => '#FFB6C1'],
            ['name' => 'Cam', 'color_code' => '#FFA500'],
            ['name' => 'Cam đậm', 'color_code' => '#FF8C00'],
            ['name' => 'Cam nhạt', 'color_code' => '#FFDAB9'],
            ['name' => 'Đen', 'color_code' => '#000000'],
            ['name' => 'Trắng', 'color_code' => '#FFFFFF'],
            ['name' => 'Xám', 'color_code' => '#808080'],
            ['name' => 'Xám đậm', 'color_code' => '#696969'],
            ['name' => 'Xám nhạt', 'color_code' => '#D3D3D3'],
            ['name' => 'Vàng gold', 'color_code' => '#FFD700'],
            ['name' => 'Bạc', 'color_code' => '#C0C0C0'],
            ['name' => 'Nâu', 'color_code' => '#8B4513'],
            ['name' => 'Nâu nhạt', 'color_code' => '#DEB887'],
            ['name' => 'Nâu đậm', 'color_code' => '#5C4033'],
            ['name' => 'Ngọc lam', 'color_code' => '#40E0D0'],
            ['name' => 'Hồng phấn', 'color_code' => '#FFE4E1'],
            ['name' => 'Xanh ngọc', 'color_code' => '#008080'],
            ['name' => 'Tím hoa cà', 'color_code' => '#9370DB'],
        ];

        foreach ($colors as $color) {
            Color::updateOrCreate(
                ['name' => $color['name']], 
                $color
            );
        }
    }
}
