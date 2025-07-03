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
            ['name' => 'Đỏ tươi', 'color_code' => '#FF1744'],
            ['name' => 'Đỏ đậm', 'color_code' => '#D50000'],
            ['name' => 'Xanh lá', 'color_code' => '#00FF00'],
            ['name' => 'Xanh lá đậm', 'color_code' => '#1B5E20'],
            ['name' => 'Xanh lá nhạt', 'color_code' => '#A5D6A7'],
            ['name' => 'Xanh dương', 'color_code' => '#0000FF'],
            ['name' => 'Xanh dương nhạt', 'color_code' => '#90CAF9'],
            ['name' => 'Xanh dương đậm', 'color_code' => '#0D47A1'],
            ['name' => 'Vàng', 'color_code' => '#FFFF00'],
            ['name' => 'Vàng nhạt', 'color_code' => '#FFF59D'],
            ['name' => 'Vàng đậm', 'color_code' => '#FFD600'],
            ['name' => 'Tím', 'color_code' => '#800080'],
            ['name' => 'Tím nhạt', 'color_code' => '#CE93D8'],
            ['name' => 'Tím đậm', 'color_code' => '#4A148C'],
            ['name' => 'Hồng', 'color_code' => '#FF69B4'],
            ['name' => 'Hồng đậm', 'color_code' => '#C2185B'],
            ['name' => 'Hồng nhạt', 'color_code' => '#F8BBD0'],
            ['name' => 'Cam', 'color_code' => '#FFA500'],
            ['name' => 'Cam đậm', 'color_code' => '#E65100'],
            ['name' => 'Cam nhạt', 'color_code' => '#FFCC80'],
            ['name' => 'Đen', 'color_code' => '#000000'],
            ['name' => 'Trắng', 'color_code' => '#FFFFFF'],
            ['name' => 'Xám', 'color_code' => '#808080'],
            ['name' => 'Xám đậm', 'color_code' => '#212121'],
            ['name' => 'Xám nhạt', 'color_code' => '#E0E0E0'],
            ['name' => 'Vàng gold', 'color_code' => '#FFD700'],
            ['name' => 'Bạc', 'color_code' => '#C0C0C0'],
            ['name' => 'Nâu', 'color_code' => '#8B4513'],
            ['name' => 'Nâu nhạt', 'color_code' => '#BCB6B6'],
            ['name' => 'Nâu đậm', 'color_code' => '#3E2723'],
            ['name' => 'Ngọc lam', 'color_code' => '#00BCD4'],
            ['name' => 'Hồng phấn', 'color_code' => '#FFE4E1'],
            ['name' => 'Xanh ngọc', 'color_code' => '#009688'],
            ['name' => 'Tím hoa cà', 'color_code' => '#9575CD']
        ];

        foreach ($colors as $color) {
            Color::updateOrCreate(
                ['name' => $color['name']], 
                $color
            );
        }
    }
}
