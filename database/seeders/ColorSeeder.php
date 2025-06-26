<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        $colors = [
            ['name' => 'Đỏ', 'color_code' => '#E53935'],
            ['name' => 'Đỏ tươi', 'color_code' => '#D32F2F'],
            ['name' => 'Đỏ đậm', 'color_code' => '#B71C1C'],
            ['name' => 'Xanh lá', 'color_code' => '#43A047'],
            ['name' => 'Xanh lá đậm', 'color_code' => '#2E7D32'],
            ['name' => 'Xanh lá nhạt', 'color_code' => '#81C784'],
            ['name' => 'Xanh dương', 'color_code' => '#1E88E5'],
            ['name' => 'Xanh dương nhạt', 'color_code' => '#64B5F6'],
            ['name' => 'Xanh dương đậm', 'color_code' => '#1565C0'],
            ['name' => 'Vàng', 'color_code' => '#FDD835'],
            ['name' => 'Vàng nhạt', 'color_code' => '#FFF176'],
            ['name' => 'Vàng đậm', 'color_code' => '#F9A825'],
            ['name' => 'Tím', 'color_code' => '#8E24AA'],
            ['name' => 'Tím nhạt', 'color_code' => '#BA68C8'],
            ['name' => 'Tím đậm', 'color_code' => '#6A1B9A'],
            ['name' => 'Hồng', 'color_code' => '#EC407A'],
            ['name' => 'Hồng đậm', 'color_code' => '#D81B60'],
            ['name' => 'Hồng nhạt', 'color_code' => '#F48FB1'],
            ['name' => 'Cam', 'color_code' => '#FB8C00'],
            ['name' => 'Cam đậm', 'color_code' => '#EF6C00'],
            ['name' => 'Cam nhạt', 'color_code' => '#FFB74D'],
            ['name' => 'Đen', 'color_code' => '#212121'],
            ['name' => 'Trắng', 'color_code' => '#FFFFFF'],
            ['name' => 'Xám', 'color_code' => '#757575'],
            ['name' => 'Xám đậm', 'color_code' => '#424242'],
            ['name' => 'Xám nhạt', 'color_code' => '#BDBDBD'],
            ['name' => 'Vàng gold', 'color_code' => '#FFB300'],
            ['name' => 'Bạc', 'color_code' => '#9E9E9E'],
            ['name' => 'Nâu', 'color_code' => '#795548'],
            ['name' => 'Nâu nhạt', 'color_code' => '#A1887F'],
            ['name' => 'Nâu đậm', 'color_code' => '#4E342E'],
            ['name' => 'Ngọc lam', 'color_code' => '#26A69A'],
            ['name' => 'Hồng phấn', 'color_code' => '#FFCDD2'],
            ['name' => 'Xanh ngọc', 'color_code' => '#00897B'],
            ['name' => 'Tím hoa cà', 'color_code' => '#7E57C2']
        ];

        foreach ($colors as $color) {
            Color::updateOrCreate(
                ['name' => $color['name']], 
                $color
            );
        }
    }
}
