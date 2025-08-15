<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = fake()->dateTimeBetween('-30 days', 'now');
        $checkInTime = Carbon::parse($date)->setTime(fake()->numberBetween(7, 9), fake()->numberBetween(0, 59));
        $checkOutTime = Carbon::parse($date)->setTime(fake()->numberBetween(17, 19), fake()->numberBetween(0, 59));
        
        $hasCheckOut = fake()->boolean(80); // 80% chance of having check out
        
        return [
            'user_id' => User::factory(),
            'date' => $date,
            'check_in_time' => $checkInTime,
            'check_out_time' => $hasCheckOut ? $checkOutTime : null,
            'points_earned' => fake()->numberBetween(10, 50),
            'status' => fake()->randomElement(['present', 'late', 'absent']),
            'notes' => fake()->optional()->sentence(),
            'points_claimed' => fake()->boolean(70), // 70% chance of points being claimed
        ];
    }

    /**
     * Indicate that the attendance is completed (check in and check out).
     */
    public function completed(): static
    {
        return $this->state(function (array $attributes) {
            $date = fake()->dateTimeBetween('-30 days', 'now');
            $checkInTime = Carbon::parse($date)->setTime(fake()->numberBetween(7, 9), fake()->numberBetween(0, 59));
            $checkOutTime = Carbon::parse($date)->setTime(fake()->numberBetween(17, 19), fake()->numberBetween(0, 59));
            
            return [
                'date' => $date,
                'check_in_time' => $checkInTime,
                'check_out_time' => $checkOutTime,
                'status' => 'present',
            ];
        });
    }

    /**
     * Indicate that the attendance is only check in (no check out).
     */
    public function checkInOnly(): static
    {
        return $this->state(function (array $attributes) {
            $date = fake()->dateTimeBetween('-30 days', 'now');
            $checkInTime = Carbon::parse($date)->setTime(fake()->numberBetween(7, 9), fake()->numberBetween(0, 59));
            
            return [
                'date' => $date,
                'check_in_time' => $checkInTime,
                'check_out_time' => null,
                'status' => 'present',
            ];
        });
    }

    /**
     * Indicate that the attendance is today.
     */
    public function today(): static
    {
        return $this->state(function (array $attributes) {
            $today = Carbon::today();
            $checkInTime = $today->copy()->setTime(fake()->numberBetween(7, 9), fake()->numberBetween(0, 59));
            $checkOutTime = $today->copy()->setTime(fake()->numberBetween(17, 19), fake()->numberBetween(0, 59));
            
            return [
                'date' => $today,
                'check_in_time' => $checkInTime,
                'check_out_time' => fake()->boolean(80) ? $checkOutTime : null,
            ];
        });
    }

    /**
     * Indicate that the attendance is late.
     */
    public function late(): static
    {
        return $this->state(function (array $attributes) {
            $date = fake()->dateTimeBetween('-30 days', 'now');
            $checkInTime = Carbon::parse($date)->setTime(fake()->numberBetween(9, 11), fake()->numberBetween(0, 59));
            $checkOutTime = Carbon::parse($date)->setTime(fake()->numberBetween(17, 19), fake()->numberBetween(0, 59));
            
            return [
                'date' => $date,
                'check_in_time' => $checkInTime,
                'check_out_time' => $checkOutTime,
                'status' => 'late',
            ];
        });
    }

    /**
     * Indicate that the attendance is absent.
     */
    public function absent(): static
    {
        return $this->state(function (array $attributes) {
            $date = fake()->dateTimeBetween('-30 days', 'now');
            
            return [
                'date' => $date,
                'check_in_time' => null,
                'check_out_time' => null,
                'status' => 'absent',
                'points_earned' => 0,
            ];
        });
    }
} 