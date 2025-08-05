<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create main phone brand categories
        $categories = [
            [
                'name' => 'Apple',
                'description' => 'Các sản phẩm điện thoại Apple iPhone',
                'parent_id' => null
            ],
            [
                'name' => 'Samsung',
                'description' => 'Các sản phẩm điện thoại Samsung',
                'parent_id' => null
            ],
            [
                'name' => 'Xiaomi',
                'description' => 'Các sản phẩm điện thoại Xiaomi',
                'parent_id' => null
            ],
            [
                'name' => 'OPPO',
                'description' => 'Các sản phẩm điện thoại OPPO',
                'parent_id' => null
            ],
            [
                'name' => 'Vivo',
                'description' => 'Các sản phẩm điện thoại Vivo',
                'parent_id' => null
            ],
            [
                'name' => 'Realme',
                'description' => 'Các sản phẩm điện thoại Realme',
                'parent_id' => null
            ],
            [
                'name' => 'OnePlus',
                'description' => 'Các sản phẩm điện thoại OnePlus',
                'parent_id' => null
            ],
            [
                'name' => 'Huawei',
                'description' => 'Các sản phẩm điện thoại Huawei',
                'parent_id' => null
            ],
            [
                'name' => 'Nokia',
                'description' => 'Các sản phẩm điện thoại Nokia',
                'parent_id' => null
            ],
            [
                'name' => 'Motorola',
                'description' => 'Các sản phẩm điện thoại Motorola',
                'parent_id' => null
            ],
            [
                'name' => 'ASUS',
                'description' => 'Các sản phẩm điện thoại ASUS',
                'parent_id' => null
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
