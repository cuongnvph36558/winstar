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
        $receiverNames = [
            'Nguyễn Văn An',
            'Trần Thị Bình',
            'Lê Văn Cường',
            'Phạm Thị Dung',
            'Hoàng Văn Em',
            'Vũ Thị Phương',
            'Đặng Văn Hùng',
            'Bùi Thị Thảo',
            'Ngô Văn Dũng',
            'Lý Thị Nga'
        ];

        $cities = [
            'Hà Nội',
            'TP. Hồ Chí Minh',
            'Đà Nẵng',
            'Cần Thơ',
            'Huế',
            'Nha Trang',
            'Đà Lạt',
            'Vũng Tàu',
            'Hải Phòng',
            'Quảng Ninh'
        ];

        $districts = [
            'Đống Đa',
            'Ba Đình',
            'Hoàn Kiếm',
            'Hai Bà Trưng',
            'Tây Hồ',
            'Quận 1',
            'Quận 2',
            'Quận 3',
            'Quận 4',
            'Quận 5'
        ];

        $wards = [
            'Láng Hạ',
            'Phúc Xá',
            'Hàng Bạc',
            'Bạch Mai',
            'Tứ Liên',
            'Bến Nghé',
            'Thảo Điền',
            'Võ Thị Sáu',
            'Cầu Kho',
            'Phường 1'
        ];

        $descriptions = [
            'Đơn hàng điện thoại thông minh',
            'Mua sắm online',
            'Đặt hàng qua website',
            'Mua điện thoại mới',
            'Đơn hàng khuyến mãi',
            'Mua sắm cuối tuần',
            'Đặt hàng giao hàng nhanh',
            'Mua điện thoại tặng quà',
            'Đơn hàng thanh toán online',
            'Mua sắm tích điểm'
        ];

        return [
            'user_id' => User::factory(),
            'code_order' => 'ORD-' . fake()->unique()->numerify('#####'),
            'receiver_name' => $this->faker->randomElement($receiverNames),
            'billing_city' => $this->faker->randomElement($cities),
            'billing_district' => $this->faker->randomElement($districts),
            'billing_ward' => $this->faker->randomElement($wards),
            'billing_address' => $this->faker->numberBetween(1, 999) . ' ' . $this->faker->randomElement(['Nguyễn Trãi', 'Lê Lợi', 'Trần Hưng Đạo', 'Lý Thường Kiệt', 'Nguyễn Huệ']),
            'phone' => fake()->numerify('0#########'),
            'description' => $this->faker->randomElement($descriptions),
            'total_amount' => fake()->randomFloat(2, 1000000, 50000000),
            'payment_method' => fake()->randomElement(['vnpay', 'cod']),
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