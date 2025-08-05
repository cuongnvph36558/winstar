<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        $colors = [
            // Màu cơ bản cho điện thoại
            ['name' => 'Đen', 'color_code' => '#000000', 'hex_code' => '#000000'],
            ['name' => 'Trắng', 'color_code' => '#FFFFFF', 'hex_code' => '#FFFFFF'],
            ['name' => 'Xám', 'color_code' => '#808080', 'hex_code' => '#808080'],
            ['name' => 'Bạc', 'color_code' => '#C0C0C0', 'hex_code' => '#C0C0C0'],
            ['name' => 'Vàng', 'color_code' => '#FFD700', 'hex_code' => '#FFD700'],
            ['name' => 'Hồng', 'color_code' => '#FFC0CB', 'hex_code' => '#FFC0CB'],
            ['name' => 'Đỏ', 'color_code' => '#FF0000', 'hex_code' => '#FF0000'],
            ['name' => 'Xanh dương', 'color_code' => '#0000FF', 'hex_code' => '#0000FF'],
            ['name' => 'Xanh lá', 'color_code' => '#00FF00', 'hex_code' => '#00FF00'],
            ['name' => 'Tím', 'color_code' => '#800080', 'hex_code' => '#800080'],

            // Màu iPhone
            ['name' => 'Xám không gian', 'color_code' => '#2C2C2C', 'hex_code' => '#2C2C2C'],
            ['name' => 'Trắng ánh sao', 'color_code' => '#F5F2EB', 'hex_code' => '#F5F2EB'],
            ['name' => 'Đen bóng đêm', 'color_code' => '#1E1E2F', 'hex_code' => '#1E1E2F'],
            ['name' => 'Xanh biển', 'color_code' => '#4B9CD3', 'hex_code' => '#4B9CD3'],
            ['name' => 'Cam san hô', 'color_code' => '#FF6F61', 'hex_code' => '#FF6F61'],
            ['name' => 'Titan tự nhiên', 'color_code' => '#D6D6D6', 'hex_code' => '#D6D6D6'],
            ['name' => 'Titan trắng', 'color_code' => '#F8F8F8', 'hex_code' => '#F8F8F8'],
            ['name' => 'Titan đen', 'color_code' => '#1A1A1A', 'hex_code' => '#1A1A1A'],
            ['name' => 'Titan xanh', 'color_code' => '#4D6A78', 'hex_code' => '#4D6A78'],
            ['name' => 'Đỏ sản phẩm', 'color_code' => '#BE0032', 'hex_code' => '#BE0032'],

            // Màu Samsung
            ['name' => 'Xanh navy', 'color_code' => '#000080', 'hex_code' => '#000080'],
            ['name' => 'Xanh rêu', 'color_code' => '#4B5320', 'hex_code' => '#4B5320'],
            ['name' => 'Xanh ngọc', 'color_code' => '#40E0D0', 'hex_code' => '#40E0D0'],
            ['name' => 'Xanh mint', 'color_code' => '#98FF98', 'hex_code' => '#98FF98'],
            ['name' => 'Tím lavender', 'color_code' => '#E6E6FA', 'hex_code' => '#E6E6FA'],
            ['name' => 'Hồng rose', 'color_code' => '#FFE4E1', 'hex_code' => '#FFE4E1'],
            ['name' => 'Vàng gold', 'color_code' => '#FFD700', 'hex_code' => '#FFD700'],
            ['name' => 'Đen phantom', 'color_code' => '#1A1A1A', 'hex_code' => '#1A1A1A'],
            ['name' => 'Trắng cream', 'color_code' => '#FFFDD0', 'hex_code' => '#FFFDD0'],

            // Màu Xiaomi
            ['name' => 'Xanh ocean', 'color_code' => '#006994', 'hex_code' => '#006994'],
            ['name' => 'Đen carbon', 'color_code' => '#2C2C2C', 'hex_code' => '#2C2C2C'],
            ['name' => 'Trắng pearl', 'color_code' => '#F5F5F5', 'hex_code' => '#F5F5F5'],
            ['name' => 'Xanh forest', 'color_code' => '#228B22', 'hex_code' => '#228B22'],
            ['name' => 'Tím violet', 'color_code' => '#8A2BE2', 'hex_code' => '#8A2BE2'],

            // Màu OPPO
            ['name' => 'Xanh sky', 'color_code' => '#87CEEB', 'hex_code' => '#87CEEB'],
            ['name' => 'Đen midnight', 'color_code' => '#191970', 'hex_code' => '#191970'],
            ['name' => 'Trắng snow', 'color_code' => '#FFFAFA', 'hex_code' => '#FFFAFA'],
            ['name' => 'Xanh emerald', 'color_code' => '#50C878', 'hex_code' => '#50C878'],
            ['name' => 'Hồng sakura', 'color_code' => '#FFB7C5', 'hex_code' => '#FFB7C5'],

            // Màu Vivo
            ['name' => 'Xanh sapphire', 'color_code' => '#0F52BA', 'hex_code' => '#0F52BA'],
            ['name' => 'Đen obsidian', 'color_code' => '#1A1A1A', 'hex_code' => '#1A1A1A'],
            ['name' => 'Trắng crystal', 'color_code' => '#F8F8FF', 'hex_code' => '#F8F8FF'],
            ['name' => 'Xanh jade', 'color_code' => '#00A86B', 'hex_code' => '#00A86B'],
            ['name' => 'Tím amethyst', 'color_code' => '#9966CC', 'hex_code' => '#9966CC']
        ];

        foreach ($colors as $color) {
            Color::updateOrCreate(
                ['name' => $color['name']],
                $color
            );
        }
    }
}
