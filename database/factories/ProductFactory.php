<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $names = [
            'Điện thoại thông minh cao cấp',
            'Smartphone hiệu năng mạnh',
            'Điện thoại camera chuyên nghiệp',
            'Smartphone thiết kế đẹp',
            'Điện thoại pin trâu',
            'Smartphone màn hình lớn',
            'Điện thoại chơi game',
            'Smartphone chụp ảnh đẹp',
            'Điện thoại bảo mật cao',
            'Smartphone đa nhiệm'
        ];

        $descriptions = [
            'Điện thoại thông minh với thiết kế hiện đại, hiệu năng mạnh mẽ và camera chất lượng cao.',
            'Smartphone được trang bị chip xử lý mới nhất, màn hình sắc nét và pin dung lượng lớn.',
            'Điện thoại chuyên về nhiếp ảnh với camera độ phân giải cao và nhiều tính năng chụp ảnh.',
            'Smartphone có thiết kế đẹp mắt, màn hình cong và màu sắc bắt mắt.',
            'Điện thoại với pin dung lượng lớn, sạc nhanh và tiết kiệm năng lượng.',
            'Smartphone màn hình lớn, phù hợp cho việc xem phim và chơi game.',
            'Điện thoại được tối ưu cho việc chơi game với hiệu năng cao và tản nhiệt tốt.',
            'Smartphone camera chất lượng cao, chụp ảnh đẹp trong mọi điều kiện ánh sáng.',
            'Điện thoại với các tính năng bảo mật tiên tiến, bảo vệ thông tin cá nhân.',
            'Smartphone đa nhiệm, xử lý nhiều tác vụ cùng lúc một cách mượt mà.'
        ];

        return [
            'name' => $this->faker->randomElement($names),
            'image' => 'default.jpg',
            'price' => $this->faker->numberBetween(1000000, 50000000), // Giá từ 1 triệu đến 50 triệu
            'promotion_price' => $this->faker->numberBetween(800000, 45000000), // Giá khuyến mãi
            'description' => $this->faker->randomElement($descriptions),
            'category_id' => Category::factory(),
            'status' => 1,
            'view' => $this->faker->numberBetween(0, 1000),
            'stock_quantity' => $this->faker->numberBetween(0, 1000),
        ];
    }
}
