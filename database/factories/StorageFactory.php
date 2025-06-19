<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StorageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'capacity' => $this->faker->randomElement(['64GB', '128GB', '256GB', '512GB', '1TB']),
        ];
    }
}
