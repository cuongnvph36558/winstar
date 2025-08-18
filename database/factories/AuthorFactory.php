<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Author>
 */
class AuthorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $names = [
            'Nguyễn Văn Minh',
            'Trần Thị Lan',
            'Lê Hoàng Nam',
            'Phạm Thị Hương',
            'Hoàng Văn Tuấn',
            'Vũ Thị Mai',
            'Đặng Văn Hùng',
            'Bùi Thị Thảo',
            'Ngô Văn Dũng',
            'Lý Thị Nga'
        ];

        $bios = [
            'Chuyên gia công nghệ với hơn 10 năm kinh nghiệm trong lĩnh vực điện thoại di động.',
            'Nhà báo công nghệ, chuyên viết về các sản phẩm điện tử tiêu dùng.',
            'Kỹ sư phần mềm, chuyên gia về hệ điều hành mobile.',
            'Chuyên gia đánh giá sản phẩm công nghệ với góc nhìn khách quan.',
            'Nhà phân tích thị trường công nghệ với nhiều năm kinh nghiệm.',
            'Chuyên gia bảo mật và an toàn thông tin di động.',
            'Kỹ sư phần cứng, chuyên về thiết kế chip và bo mạch.',
            'Chuyên gia về trải nghiệm người dùng và thiết kế giao diện.',
            'Nhà phát triển ứng dụng mobile với nhiều dự án thành công.',
            'Chuyên gia về camera và công nghệ chụp ảnh trên điện thoại.'
        ];

        return [
            'name' => $this->faker->randomElement($names),
            'email' => $this->faker->unique()->safeEmail(),
            'bio' => $this->faker->randomElement($bios),
            'avatar' => $this->faker->imageUrl(200, 200, 'people'),
            'website' => $this->faker->optional()->url(),
        ];
    }
}
