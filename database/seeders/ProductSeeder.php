<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Apple Products
            [
                'name' => 'iPhone 15 Pro Max',
                'image' => 'iphone-15-pro-max.jpg',
                'price' => 34990000,
                'promotion_price' => 32990000,
                'description' => 'iPhone 15 Pro Max với chip A17 Pro, camera 48MP, màn hình 6.7 inch Super Retina XDR OLED',
                'category_id' => Category::where('name', 'Apple')->first()->id,
                'status' => 1,
                'view' => 1250,
                'stock_quantity' => 50
            ],
            [
                'name' => 'iPhone 15 Pro',
                'image' => 'iphone-15-pro.jpg',
                'price' => 29990000,
                'promotion_price' => 27990000,
                'description' => 'iPhone 15 Pro với chip A17 Pro, camera 48MP, màn hình 6.1 inch Super Retina XDR OLED',
                'category_id' => Category::where('name', 'Apple')->first()->id,
                'status' => 1,
                'view' => 980,
                'stock_quantity' => 45
            ],
            [
                'name' => 'iPhone 15',
                'image' => 'iphone-15.jpg',
                'price' => 24990000,
                'promotion_price' => 22990000,
                'description' => 'iPhone 15 với chip A16 Bionic, camera 48MP, màn hình 6.1 inch Super Retina XDR OLED',
                'category_id' => Category::where('name', 'Apple')->first()->id,
                'status' => 1,
                'view' => 850,
                'stock_quantity' => 60
            ],

            // Samsung Products
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'image' => 'galaxy-s24-ultra.jpg',
                'price' => 31990000,
                'promotion_price' => 29990000,
                'description' => 'Galaxy S24 Ultra với chip Snapdragon 8 Gen 3, camera 200MP, màn hình 6.8 inch Dynamic AMOLED 2X',
                'category_id' => Category::where('name', 'Samsung')->first()->id,
                'status' => 1,
                'view' => 1100,
                'stock_quantity' => 40
            ],
            [
                'name' => 'Samsung Galaxy S24+',
                'image' => 'galaxy-s24-plus.jpg',
                'price' => 26990000,
                'promotion_price' => 24990000,
                'description' => 'Galaxy S24+ với chip Snapdragon 8 Gen 3, camera 50MP, màn hình 6.7 inch Dynamic AMOLED 2X',
                'category_id' => Category::where('name', 'Samsung')->first()->id,
                'status' => 1,
                'view' => 920,
                'stock_quantity' => 55
            ],
            [
                'name' => 'Samsung Galaxy A55',
                'image' => 'galaxy-a55.jpg',
                'price' => 12990000,
                'promotion_price' => 11990000,
                'description' => 'Galaxy A55 với chip Exynos 1480, camera 50MP, màn hình 6.6 inch Super AMOLED',
                'category_id' => Category::where('name', 'Samsung')->first()->id,
                'status' => 1,
                'view' => 750,
                'stock_quantity' => 70
            ],

            // Xiaomi Products
            [
                'name' => 'Xiaomi 14 Ultra',
                'image' => 'xiaomi-14-ultra.jpg',
                'price' => 24990000,
                'promotion_price' => 22990000,
                'description' => 'Xiaomi 14 Ultra với chip Snapdragon 8 Gen 3, camera 50MP Leica, màn hình 6.73 inch LTPO AMOLED',
                'category_id' => Category::where('name', 'Xiaomi')->first()->id,
                'status' => 1,
                'view' => 680,
                'stock_quantity' => 35
            ],
            [
                'name' => 'Xiaomi 14',
                'image' => 'xiaomi-14.jpg',
                'price' => 19990000,
                'promotion_price' => 17990000,
                'description' => 'Xiaomi 14 với chip Snapdragon 8 Gen 3, camera 50MP Leica, màn hình 6.36 inch LTPO AMOLED',
                'category_id' => Category::where('name', 'Xiaomi')->first()->id,
                'status' => 1,
                'view' => 590,
                'stock_quantity' => 50
            ],
            [
                'name' => 'Xiaomi Redmi Note 13 Pro+',
                'image' => 'redmi-note-13-pro-plus.jpg',
                'price' => 8990000,
                'promotion_price' => 7990000,
                'description' => 'Redmi Note 13 Pro+ với chip Dimensity 7200 Ultra, camera 200MP, màn hình 6.67 inch AMOLED',
                'category_id' => Category::where('name', 'Xiaomi')->first()->id,
                'status' => 1,
                'view' => 820,
                'stock_quantity' => 80
            ],

            // OPPO Products
            [
                'name' => 'OPPO Find X7 Ultra',
                'image' => 'oppo-find-x7-ultra.jpg',
                'price' => 22990000,
                'promotion_price' => 20990000,
                'description' => 'OPPO Find X7 Ultra với chip Dimensity 9300, camera 50MP Hasselblad, màn hình 6.82 inch LTPO AMOLED',
                'category_id' => Category::where('name', 'OPPO')->first()->id,
                'status' => 1,
                'view' => 450,
                'stock_quantity' => 30
            ],
            [
                'name' => 'OPPO Find X7',
                'image' => 'oppo-find-x7.jpg',
                'price' => 18990000,
                'promotion_price' => 16990000,
                'description' => 'OPPO Find X7 với chip Dimensity 9300, camera 50MP Hasselblad, màn hình 6.78 inch LTPO AMOLED',
                'category_id' => Category::where('name', 'OPPO')->first()->id,
                'status' => 1,
                'view' => 380,
                'stock_quantity' => 45
            ],
            [
                'name' => 'OPPO Reno 11',
                'image' => 'oppo-reno-11.jpg',
                'price' => 9990000,
                'promotion_price' => 8990000,
                'description' => 'OPPO Reno 11 với chip Dimensity 7050, camera 50MP, màn hình 6.7 inch AMOLED',
                'category_id' => Category::where('name', 'OPPO')->first()->id,
                'status' => 1,
                'view' => 520,
                'stock_quantity' => 65
            ],

            // Vivo Products
            [
                'name' => 'Vivo X100 Pro',
                'image' => 'vivo-x100-pro.jpg',
                'price' => 21990000,
                'promotion_price' => 19990000,
                'description' => 'Vivo X100 Pro với chip Dimensity 9300, camera 50MP Zeiss, màn hình 6.78 inch LTPO AMOLED',
                'category_id' => Category::where('name', 'Vivo')->first()->id,
                'status' => 1,
                'view' => 420,
                'stock_quantity' => 25
            ],
            [
                'name' => 'Vivo X100',
                'image' => 'vivo-x100.jpg',
                'price' => 17990000,
                'promotion_price' => 15990000,
                'description' => 'Vivo X100 với chip Dimensity 9300, camera 50MP Zeiss, màn hình 6.78 inch LTPO AMOLED',
                'category_id' => Category::where('name', 'Vivo')->first()->id,
                'status' => 1,
                'view' => 350,
                'stock_quantity' => 40
            ],
            [
                'name' => 'Vivo V29',
                'image' => 'vivo-v29.jpg',
                'price' => 12990000,
                'promotion_price' => 11990000,
                'description' => 'Vivo V29 với chip Snapdragon 778G, camera 50MP, màn hình 6.78 inch AMOLED',
                'category_id' => Category::where('name', 'Vivo')->first()->id,
                'status' => 1,
                'view' => 480,
                'stock_quantity' => 55
            ],

            // Realme Products
            [
                'name' => 'Realme GT Neo 5 SE',
                'image' => 'realme-gt-neo-5-se.jpg',
                'price' => 8990000,
                'promotion_price' => 7990000,
                'description' => 'Realme GT Neo 5 SE với chip Snapdragon 7+ Gen 2, camera 64MP, màn hình 6.74 inch AMOLED',
                'category_id' => Category::where('name', 'Realme')->first()->id,
                'status' => 1,
                'view' => 380,
                'stock_quantity' => 60
            ],
            [
                'name' => 'Realme 11 Pro+',
                'image' => 'realme-11-pro-plus.jpg',
                'price' => 12990000,
                'promotion_price' => 11990000,
                'description' => 'Realme 11 Pro+ với chip Dimensity 7050, camera 200MP, màn hình 6.7 inch AMOLED',
                'category_id' => Category::where('name', 'Realme')->first()->id,
                'status' => 1,
                'view' => 420,
                'stock_quantity' => 45
            ],

            // OnePlus Products
            [
                'name' => 'OnePlus 12',
                'image' => 'oneplus-12.jpg',
                'price' => 19990000,
                'promotion_price' => 17990000,
                'description' => 'OnePlus 12 với chip Snapdragon 8 Gen 3, camera 50MP Hasselblad, màn hình 6.82 inch LTPO AMOLED',
                'category_id' => Category::where('name', 'OnePlus')->first()->id,
                'status' => 1,
                'view' => 350,
                'stock_quantity' => 30
            ],
            [
                'name' => 'OnePlus 12R',
                'image' => 'oneplus-12r.jpg',
                'price' => 15990000,
                'promotion_price' => 13990000,
                'description' => 'OnePlus 12R với chip Snapdragon 8 Gen 2, camera 50MP, màn hình 6.78 inch LTPO AMOLED',
                'category_id' => Category::where('name', 'OnePlus')->first()->id,
                'status' => 1,
                'view' => 280,
                'stock_quantity' => 40
            ],

            // Huawei Products
            [
                'name' => 'Huawei Pura 70 Ultra',
                'image' => 'huawei-pura-70-ultra.jpg',
                'price' => 24990000,
                'promotion_price' => 22990000,
                'description' => 'Huawei Pura 70 Ultra với chip Kirin 9010, camera 50MP XMAGE, màn hình 6.8 inch LTPO OLED',
                'category_id' => Category::where('name', 'Huawei')->first()->id,
                'status' => 1,
                'view' => 200,
                'stock_quantity' => 20
            ],
            [
                'name' => 'Huawei Nova 12',
                'image' => 'huawei-nova-12.jpg',
                'price' => 12990000,
                'promotion_price' => 11990000,
                'description' => 'Huawei Nova 12 với chip Kirin 830, camera 50MP, màn hình 6.7 inch OLED',
                'category_id' => Category::where('name', 'Huawei')->first()->id,
                'status' => 1,
                'view' => 180,
                'stock_quantity' => 35
            ],

            // Nokia Products
            [
                'name' => 'Nokia G60',
                'image' => 'nokia-g60.jpg',
                'price' => 5990000,
                'promotion_price' => 4990000,
                'description' => 'Nokia G60 với chip Snapdragon 695, camera 50MP, màn hình 6.58 inch IPS LCD',
                'category_id' => Category::where('name', 'Nokia')->first()->id,
                'status' => 1,
                'view' => 150,
                'stock_quantity' => 50
            ],

            // Motorola Products
            [
                'name' => 'Motorola Edge 40',
                'image' => 'motorola-edge-40.jpg',
                'price' => 9990000,
                'promotion_price' => 8990000,
                'description' => 'Motorola Edge 40 với chip Dimensity 8020, camera 50MP, màn hình 6.55 inch pOLED',
                'category_id' => Category::where('name', 'Motorola')->first()->id,
                'status' => 1,
                'view' => 220,
                'stock_quantity' => 40
            ],

            // ASUS Products
            [
                'name' => 'ASUS ROG Phone 8',
                'image' => 'asus-rog-phone-8.jpg',
                'price' => 22990000,
                'promotion_price' => 20990000,
                'description' => 'ASUS ROG Phone 8 với chip Snapdragon 8 Gen 3, camera 50MP, màn hình 6.78 inch AMOLED',
                'category_id' => Category::where('name', 'ASUS')->first()->id,
                'status' => 1,
                'view' => 180,
                'stock_quantity' => 25
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
