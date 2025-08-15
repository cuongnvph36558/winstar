<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Storage>
 */
class StorageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'capacity' => fake()->randomElement(['64GB', '128GB', '256GB', '512GB', '1TB', '2TB']),
        ];
    }

    /**
     * Indicate that the storage is 128GB.
     */
    public function gb128(): static
    {
        return $this->state(fn (array $attributes) => [
            'capacity' => '128GB',
        ]);
    }

    /**
     * Indicate that the storage is 256GB.
     */
    public function gb256(): static
    {
        return $this->state(fn (array $attributes) => [
            'capacity' => '256GB',
        ]);
    }

    /**
     * Indicate that the storage is 512GB.
     */
    public function gb512(): static
    {
        return $this->state(fn (array $attributes) => [
            'capacity' => '512GB',
        ]);
    }

    /**
     * Indicate that the storage is 1TB.
     */
    public function tb1(): static
    {
        return $this->state(fn (array $attributes) => [
            'capacity' => '1TB',
        ]);
    }
}
