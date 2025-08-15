<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'description' => $this->faker->sentence(),
        ];
    }

    /**
     * Indicate that the role is admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'admin',
            'description' => 'Administrator role',
        ]);
    }

    /**
     * Indicate that the role is user.
     */
    public function user(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'user',
            'description' => 'Regular user role',
        ]);
    }

    /**
     * Indicate that the role is moderator.
     */
    public function moderator(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'moderator',
            'description' => 'Moderator role',
        ]);
    }
} 