<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductVariantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'variant_name' => 'Biến thể ' . $this->faker->word,
            'image_variant' => 'variant.jpg',
            'color' => $this->faker->safeColorName,
            'size' => $this->faker->randomElement(['S', 'M', 'L', 'XL']),
            'storage' => $this->faker->randomElement(['64GB', '128GB', '256GB']),
            'price' => $this->faker->randomFloat(2, 100, 999),
            'stock_quantity' => $this->faker->numberBetween(1, 200),
            'sku' => strtoupper(Str::random(8)),
        ];
    }
}
