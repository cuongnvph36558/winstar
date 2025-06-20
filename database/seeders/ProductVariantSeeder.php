<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        Product::all()->each(function ($product) {
            ProductVariant::factory(2)->create([
                'product_id' => $product->id
            ]);
        });
    }
}
