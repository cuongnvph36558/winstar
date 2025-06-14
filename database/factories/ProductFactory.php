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
            'description' => $this->faker->paragraph,
            'category_id' => Category::inRandomOrder()->first()->id ?? 1,
            'status' => 1,
            'view' => $this->faker->numberBetween(0, 1000),
        ];
    }
}
