<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PointVoucher>
 */
class PointVoucherFactory extends Factory
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
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'points_required' => fake()->numberBetween(100, 2000),
            'discount_value' => $discountValue,
            'discount_type' => $discountType,
            'min_order_value' => fake()->numberBetween(100000, 1000000),
            'max_usage' => fake()->numberBetween(10, 1000),
            'current_usage' => fake()->numberBetween(0, 50),
            'start_date' => Carbon::now()->subDays(fake()->numberBetween(0, 30)),
            'end_date' => Carbon::now()->addDays(fake()->numberBetween(30, 365)),
            'is_active' => fake()->boolean(80), // 80% chance of being active
        ];
    }

    /**
     * Indicate that the voucher is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
            'start_date' => Carbon::now()->subDays(fake()->numberBetween(0, 30)),
            'end_date' => Carbon::now()->addDays(fake()->numberBetween(30, 365)),
        ]);
    }

    /**
     * Indicate that the voucher is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the voucher is expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
            'start_date' => Carbon::now()->subDays(fake()->numberBetween(60, 90)),
            'end_date' => Carbon::now()->subDays(fake()->numberBetween(1, 30)),
        ]);
    }

    /**
     * Indicate that the voucher is percentage discount.
     */
    public function percentage(): static
    {
        return $this->state(fn (array $attributes) => [
            'discount_type' => 'percentage',
            'discount_value' => fake()->numberBetween(5, 50),
        ]);
    }

    /**
     * Indicate that the voucher is fixed amount discount.
     */
    public function fixed(): static
    {
        return $this->state(fn (array $attributes) => [
            'discount_type' => 'fixed',
            'discount_value' => fake()->numberBetween(50000, 500000),
        ]);
    }
} 