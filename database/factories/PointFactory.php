<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Point>
 */
class PointFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $earnedPoints = fake()->numberBetween(0, 10000);
        $usedPoints = fake()->numberBetween(0, $earnedPoints);
        $expiredPoints = fake()->numberBetween(0, 1000);
        $totalPoints = $earnedPoints - $usedPoints - $expiredPoints;

        return [
            'user_id' => User::factory(),
            'total_points' => $totalPoints,
            'earned_points' => $earnedPoints,
            'used_points' => $usedPoints,
            'expired_points' => $expiredPoints,
            'vip_level' => fake()->randomElement(['Bronze', 'Silver', 'Gold', 'Platinum', 'Diamond']),
        ];
    }

    /**
     * Indicate that the user has bronze level.
     */
    public function bronze(): static
    {
        return $this->state(fn (array $attributes) => [
            'earned_points' => fake()->numberBetween(0, 499),
            'vip_level' => 'Bronze',
        ]);
    }

    /**
     * Indicate that the user has silver level.
     */
    public function silver(): static
    {
        return $this->state(fn (array $attributes) => [
            'earned_points' => fake()->numberBetween(500, 1999),
            'vip_level' => 'Silver',
        ]);
    }

    /**
     * Indicate that the user has gold level.
     */
    public function gold(): static
    {
        return $this->state(fn (array $attributes) => [
            'earned_points' => fake()->numberBetween(2000, 4999),
            'vip_level' => 'Gold',
        ]);
    }

    /**
     * Indicate that the user has platinum level.
     */
    public function platinum(): static
    {
        return $this->state(fn (array $attributes) => [
            'earned_points' => fake()->numberBetween(5000, 9999),
            'vip_level' => 'Platinum',
        ]);
    }

    /**
     * Indicate that the user has diamond level.
     */
    public function diamond(): static
    {
        return $this->state(fn (array $attributes) => [
            'earned_points' => fake()->numberBetween(10000, 50000),
            'vip_level' => 'Diamond',
        ]);
    }
} 