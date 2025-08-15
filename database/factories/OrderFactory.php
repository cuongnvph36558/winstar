<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Coupon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'code_order' => 'ORD-' . fake()->unique()->numerify('#####'),
            'receiver_name' => fake()->name(),
            'billing_city' => fake()->city(),
            'billing_district' => fake()->city(),
            'billing_ward' => fake()->city(),
            'billing_address' => fake()->address(),
            'phone' => fake()->numerify('0#########'),
            'description' => fake()->sentence(),
            'total_amount' => fake()->randomFloat(2, 100000, 5000000),
            'payment_method' => fake()->randomElement(['momo', 'vnpay', 'cod']),
            'status' => fake()->randomElement(['pending', 'processing', 'shipping', 'completed', 'cancelled']),
            'is_received' => fake()->boolean(),
            'coupon_id' => null,
            'discount_amount' => 0,
            'payment_status' => fake()->randomElement(['pending', 'paid', 'failed']),
            'points_earned' => fake()->numberBetween(0, 1000),
            'points_used' => fake()->numberBetween(0, 500),
            'point_voucher_code' => null,
        ];
    }

    /**
     * Indicate that the order is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);
    }

    /**
     * Indicate that the order is processing.
     */
    public function processing(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'processing',
            'payment_status' => 'paid',
        ]);
    }

    /**
     * Indicate that the order is delivered.
     */
    public function delivered(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'delivered',
            'payment_status' => 'paid',
            'is_received' => true,
        ]);
    }
} 