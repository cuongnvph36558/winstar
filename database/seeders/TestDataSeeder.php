<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Post;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo authors
        $authors = [
            ['name' => 'Admin User', 'email' => 'admin@example.com', 'bio' => 'Administrator'],
            ['name' => 'Editor', 'email' => 'editor@example.com', 'bio' => 'Content Editor'],
            ['name' => 'Writer', 'email' => 'writer@example.com', 'bio' => 'Content Writer'],
        ];

        foreach ($authors as $authorData) {
            Author::firstOrCreate(
                ['email' => $authorData['email']],
                $authorData
            );
        }

        // Tạo posts
        $titles = [
            'Hướng dẫn sử dụng sản phẩm mới',
            'Xu hướng công nghệ 2024',
            'Cách chọn sản phẩm phù hợp',
            'Tin tức mới nhất về ngành công nghiệp',
            'Đánh giá sản phẩm chất lượng cao',
            'Tips và tricks cho người dùng',
            'Cập nhật tính năng mới',
            'So sánh các sản phẩm trên thị trường',
            'Hướng dẫn bảo trì và bảo dưỡng',
            'Thông tin khuyến mãi mới nhất'
        ];

        $authors = Author::all();
        
        foreach ($titles as $index => $title) {
            Post::firstOrCreate(
                ['title' => $title],
                [
                    'author_id' => $authors->random()->id,
                    'content' => 'Đây là nội dung chi tiết của bài viết "' . $title . '". Bài viết này cung cấp thông tin hữu ích cho người đọc về các chủ đề liên quan đến sản phẩm và dịch vụ của chúng tôi.',
                    'status' => 'published',
                    'published_at' => now()->subDays($index),
                ]
            );
        }
    }
} 