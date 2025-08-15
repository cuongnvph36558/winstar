<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Order;
use App\Models\PointVoucher;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PointTransaction>
 */
class PointTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['earn', 'use', 'expire', 'bonus']);
        $referenceType = fake()->randomElement(['order', 'voucher', 'attendance', 'bonus']);
        
        $points = match($type) {
            'earn' => fake()->numberBetween(10, 500),
            'use' => fake()->numberBetween(50, 1000),
            'expire' => fake()->numberBetween(10, 200),
            'bonus' => fake()->numberBetween(100, 2000),
            default => fake()->numberBetween(10, 500),
        };

        $description = match($type) {
            'earn' => fake()->randomElement([
                'Tích điểm từ đơn hàng',
                'Tích điểm từ check-in',
                'Tích điểm từ đánh giá sản phẩm'
            ]),
            'use' => 'Sử dụng điểm đổi voucher',
            'expire' => 'Điểm hết hạn',
            'bonus' => fake()->randomElement([
                'Điểm thưởng sinh nhật',
                'Điểm thưởng khuyến mãi',
                'Điểm thưởng VIP'
            ]),
            default => 'Giao dịch điểm',
        };

        return [
            'user_id' => User::factory(),
            'type' => $type,
            'points' => $points,
            'description' => $description,
            'reference_type' => $referenceType,
            'reference_id' => fake()->numberBetween(1, 100),
            'expiry_date' => Carbon::now()->addDays(fake()->numberBetween(30, 365)),
            'is_expired' => fake()->boolean(10), // 10% chance of being expired
        ];
    }

    /**
     * Indicate that the transaction is earned points.
     */
    public function earned(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'earn',
            'points' => fake()->numberBetween(10, 500),
            'description' => fake()->randomElement([
                'Tích điểm từ đơn hàng',
                'Tích điểm từ check-in',
                'Tích điểm từ đánh giá sản phẩm'
            ]),
        ]);
    }

    /**
     * Indicate that the transaction is used points.
     */
    public function used(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'use',
            'points' => fake()->numberBetween(50, 1000),
            'description' => 'Sử dụng điểm đổi voucher',
        ]);
    }

    /**
     * Indicate that the transaction is expired points.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'expire',
            'points' => fake()->numberBetween(10, 200),
            'description' => 'Điểm hết hạn',
            'is_expired' => true,
        ]);
    }

    /**
     * Indicate that the transaction is bonus points.
     */
    public function bonus(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'bonus',
            'points' => fake()->numberBetween(100, 2000),
            'description' => fake()->randomElement([
                'Điểm thưởng sinh nhật',
                'Điểm thưởng khuyến mãi',
                'Điểm thưởng VIP'
            ]),
        ]);
    }

    /**
     * Indicate that the transaction is from order.
     */
    public function fromOrder(): static
    {
        return $this->state(fn (array $attributes) => [
            'reference_type' => 'order',
            'reference_id' => Order::factory(),
        ]);
    }

    /**
     * Indicate that the transaction is from voucher.
     */
    public function fromVoucher(): static
    {
        return $this->state(fn (array $attributes) => [
            'reference_type' => 'voucher',
            'reference_id' => PointVoucher::factory(),
        ]);
    }
} 