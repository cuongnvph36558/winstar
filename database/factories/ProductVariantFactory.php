<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Color;
use App\Models\Storage;

class ProductVariantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::inRandomOrder()->first()?->id ?? Product::factory(),
            'variant_name' => $this->faker->word . ' Edition',
            'image_variant' => json_encode([
                $this->faker->imageUrl(640, 480, 'tech'),
                $this->faker->imageUrl(640, 480, 'tech'),
            ]),
            'price' => $this->faker->randomFloat(2, 100, 1000),
            'promotion_price' => $this->faker->randomFloat(2, 100, 1000),
            'stock_quantity' => $this->faker->numberBetween(1, 100),
            'color_id' => Color::inRandomOrder()->first()?->id,
            'storage_id' => Storage::inRandomOrder()->first()?->id,
        ];
    }
}
