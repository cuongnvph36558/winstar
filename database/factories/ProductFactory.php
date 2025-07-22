<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'image' => 'default.jpg',
            'price' => $this->faker->numberBetween(100000, 5000000), // Giá từ 100k đến 5 triệu
            'promotion_price' => $this->faker->numberBetween(100000, 5000000), // Giá từ 100k đến 5 triệu
            'description' => $this->faker->paragraph,
            'category_id' => Category::inRandomOrder()->first()->id ?? 1,
            'status' => 1,
            'view' => $this->faker->numberBetween(0, 1000),
        ];
    }
}
