<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $names = [
            'Nguyễn Văn An',
            'Trần Thị Bình',
            'Lê Văn Cường',
            'Phạm Thị Dung',
            'Hoàng Văn Em',
            'Vũ Thị Phương',
            'Đặng Văn Hùng',
            'Bùi Thị Thảo',
            'Ngô Văn Dũng',
            'Lý Thị Nga',
            'Trịnh Văn Minh',
            'Đỗ Thị Lan',
            'Võ Văn Nam',
            'Lương Thị Hương',
            'Hồ Văn Tuấn'
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

        return [
            'name' => $this->faker->randomElement($names),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'address' => $this->faker->numberBetween(1, 999) . ' ' . $this->faker->randomElement(['Nguyễn Trãi', 'Lê Lợi', 'Trần Hưng Đạo', 'Lý Thường Kiệt', 'Nguyễn Huệ']),
            'phone' => fake()->unique()->numerify('0#########'),
            'status' => 1,
            'avatar' => null,
            'city' => $this->faker->randomElement($cities),
            'district' => $this->faker->randomElement($districts),
            'ward' => $this->faker->randomElement($wards),
            'email_verification_code' => null,
            'email_verification_expires_at' => null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
