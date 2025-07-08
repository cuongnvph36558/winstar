<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        $colors = [
            ['name' => 'Deep Purple', 'color_code' => '#5A5366'],
            ['name' => 'Midnight', 'color_code' => '#1E1E2F'],
            ['name' => 'Starlight', 'color_code' => '#F5F2EB'],
            ['name' => 'Sierra Blue', 'color_code' => '#6495ED'],
            ['name' => 'Graphite', 'color_code' => '#3B3B4C'],
            ['name' => 'Space Gray', 'color_code' => '#2C2C2C'],
            ['name' => 'Product Red', 'color_code' => '#BE0032'],
            ['name' => 'Phantom Black', 'color_code' => '#1C1C1C'],
            ['name' => 'Phantom White', 'color_code' => '#F5F5F5'],
            ['name' => 'Titanium Bronze', 'color_code' => '#8E6A4A'],
            ['name' => 'Cosmic Black', 'color_code' => '#1A1A1A'],
            ['name' => 'Ocean Blue', 'color_code' => '#4B9CD3'],
            ['name' => 'Glowing Gold', 'color_code' => '#FFD700'],
            ['name' => 'Nebula Purple', 'color_code' => '#5D3FD3']
        ];

        foreach ($colors as $color) {
            Color::updateOrCreate(
                ['name' => $color['name']], 
                $color
            );
        }
    }
}
