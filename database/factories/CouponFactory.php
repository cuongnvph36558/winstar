<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coupon>
 */
class CouponFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $discountType = fake()->randomElement(['percentage', 'fixed']);
        $discountValue = $discountType === 'percentage' 
            ? fake()->numberBetween(5, 50) 
            : fake()->numberBetween(50000, 500000);

        return [
            'code' => strtoupper(fake()->unique()->bothify('??##')),
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'discount_type' => $discountType,
            'discount_value' => $discountValue,
            'min_order_value' => fake()->numberBetween(100000, 1000000),
            'max_discount_value' => fake()->numberBetween(100000, 1000000),
            'start_date' => Carbon::now()->subDays(fake()->numberBetween(0, 30)),
            'end_date' => Carbon::now()->addDays(fake()->numberBetween(30, 365)),
            'usage_limit' => fake()->numberBetween(10, 1000),
            'usage_limit_per_user' => fake()->numberBetween(1, 5),
            'status' => fake()->boolean(80), // 80% chance of being active
            'exchange_points' => fake()->numberBetween(0, 1000),
            'vip_level' => fake()->optional()->randomElement(['Bronze', 'Silver', 'Gold', 'Platinum', 'Diamond']),
            'validity_days' => fake()->numberBetween(30, 365),
            'max_discount' => fake()->numberBetween(100000, 1000000),
            'used_count' => fake()->numberBetween(0, 50),
        ];
    }

    /**
     * Indicate that the coupon is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => true,
            'start_date' => Carbon::now()->subDays(fake()->numberBetween(0, 30)),
            'end_date' => Carbon::now()->addDays(fake()->numberBetween(30, 365)),
        ]);
    }

    /**
     * Indicate that the coupon is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }

    /**
     * Indicate that the coupon is expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => true,
            'start_date' => Carbon::now()->subDays(fake()->numberBetween(60, 90)),
            'end_date' => Carbon::now()->subDays(fake()->numberBetween(1, 30)),
        ]);
    }

    /**
     * Indicate that the coupon is percentage discount.
     */
    public function percentage(): static
    {
        return $this->state(fn (array $attributes) => [
            'discount_type' => 'percentage',
            'discount_value' => fake()->numberBetween(5, 50),
        ]);
    }

    /**
     * Indicate that the coupon is fixed amount discount.
     */
    public function fixed(): static
    {
        return $this->state(fn (array $attributes) => [
            'discount_type' => 'fixed',
            'discount_value' => fake()->numberBetween(50000, 500000),
        ]);
    }

    /**
     * Indicate that the coupon requires minimum order value.
     */
    public function withMinOrder(): static
    {
        return $this->state(fn (array $attributes) => [
            'min_order_value' => fake()->numberBetween(100000, 1000000),
        ]);
    }

    /**
     * Indicate that the coupon has usage limit.
     */
    public function withUsageLimit(): static
    {
        return $this->state(fn (array $attributes) => [
            'usage_limit' => fake()->numberBetween(10, 100),
            'used_count' => fake()->numberBetween(0, 5),
        ]);
    }
} 