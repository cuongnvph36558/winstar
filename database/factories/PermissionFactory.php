<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Permission>
 */
class PermissionFactory extends Factory
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
     * Indicate that the permission is for user management.
     */
    public function userManagement(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'user.manage',
            'description' => 'Manage users',
        ]);
    }

    /**
     * Indicate that the permission is for product management.
     */
    public function productManagement(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'product.manage',
            'description' => 'Manage products',
        ]);
    }

    /**
     * Indicate that the permission is for order management.
     */
    public function orderManagement(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'order.manage',
            'description' => 'Manage orders',
        ]);
    }
} 