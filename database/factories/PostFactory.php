<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $titles = [
            'So sánh iPhone 15 Pro Max và Samsung Galaxy S24 Ultra',
            'Hướng dẫn chọn điện thoại phù hợp với nhu cầu',
            'Top 10 điện thoại tốt nhất năm 2024',
            'Công nghệ màn hình OLED và LCD khác nhau thế nào',
            'Đánh giá camera của Xiaomi 14 Ultra',
            'Những tính năng mới của Android 15',
            'Cách bảo vệ điện thoại khỏi virus và malware',
            'So sánh hiệu năng chip Snapdragon và Apple A17',
            'Hướng dẫn sao lưu dữ liệu điện thoại',
            'Những ứng dụng hữu ích cho điện thoại'
        ];

        $contents = [
            'Bài viết này sẽ giúp bạn hiểu rõ hơn về các tính năng và hiệu năng của hai flagship hàng đầu hiện nay.',
            'Việc chọn điện thoại phù hợp không chỉ dựa vào giá cả mà còn phải xem xét nhiều yếu tố khác.',
            'Danh sách những chiếc điện thoại được đánh giá cao nhất trong năm 2024.',
            'Tìm hiểu về công nghệ màn hình và cách phân biệt các loại màn hình khác nhau.',
            'Đánh giá chi tiết về khả năng chụp ảnh của Xiaomi 14 Ultra.',
            'Những tính năng mới và cải tiến trong phiên bản Android 15.',
            'Các biện pháp bảo vệ điện thoại khỏi các mối đe dọa bảo mật.',
            'So sánh hiệu năng giữa các chip xử lý hàng đầu hiện nay.',
            'Hướng dẫn chi tiết cách sao lưu dữ liệu quan trọng trên điện thoại.',
            'Những ứng dụng thực sự hữu ích cho cuộc sống hàng ngày.'
        ];

        return [
            'author_id' => Author::inRandomOrder()->first()?->id ?? Author::factory(),
            'title' => $this->faker->randomElement($titles),
            'content' => $this->faker->randomElement($contents) . ' ' . $this->faker->paragraph,
            'image' => $this->faker->imageUrl,
            'status' => $this->faker->randomElement(['draft', 'published']),
            'published_at' => $this->faker->dateTime,
        ];
    }
}
