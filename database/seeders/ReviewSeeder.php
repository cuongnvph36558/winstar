<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get some users and products for reviews
        $users = User::take(5)->get();
        $products = Product::take(10)->get();

        if ($users->count() == 0 || $products->count() == 0) {
            $this->command->info('Không có users hoặc products để tạo reviews. Hãy chạy seeder cho users và products trước.');
            return;
        }

        $reviewsData = [
            [
                'name' => 'Nguyễn Văn A',
                'email' => 'nguyenvana@gmail.com',
                'rating' => 5,
                'content' => 'Sản phẩm rất tốt, chất lượng vượt mong đợi. Tôi rất hài lòng với sản phẩm này. Giao hàng nhanh, đóng gói cẩn thận.',
                'status' => 1,
            ],
            [
                'name' => 'Trần Thị B',
                'email' => 'tranthib@gmail.com',
                'rating' => 4,
                'content' => 'Sản phẩm tốt, đáng tiền. Chỉ có điều hơi lâu shipping thôi. Chất lượng ổn.',
                'status' => 1,
            ],
            [
                'name' => 'Lê Văn C',
                'email' => 'levanc@gmail.com',
                'rating' => 3,
                'content' => 'Sản phẩm bình thường, không có gì đặc biệt. Giá hơi cao so với chất lượng.',
                'status' => 0,
            ],
            [
                'name' => 'Phạm Thị D',
                'email' => 'phamthid@gmail.com',
                'rating' => 5,
                'content' => 'Tuyệt vời! Sản phẩm chính xác như mô tả. Shop phục vụ rất chu đáo. Sẽ mua lại.',
                'status' => 1,
            ],
            [
                'name' => 'Hoàng Văn E',
                'email' => 'hoangvane@gmail.com',
                'rating' => 2,
                'content' => 'Sản phẩm không như mong đợi. Chất lượng kém, màu sắc không giống hình.',
                'status' => 0,
            ],
            [
                'name' => 'Vũ Thị F',
                'email' => 'vuthif@gmail.com',
                'rating' => 4,
                'content' => 'Sản phẩm khá tốt, đóng gói cẩn thận. Giao hàng đúng hẹn. Giá cả hợp lý.',
                'status' => 1,
            ],
            [
                'name' => 'Đinh Văn G',
                'email' => 'dinhvang@gmail.com',
                'rating' => 5,
                'content' => 'Chất lượng tuyệt vời! Đúng như mô tả, thậm chí còn đẹp hơn. Rất hài lòng.',
                'status' => 1,
            ],
            [
                'name' => 'Bùi Thị H',
                'email' => 'buithih@gmail.com',
                'rating' => 1,
                'content' => 'Rất thất vọng với sản phẩm này. Chất lượng kém, không đáng đồng tiền nào.',
                'status' => 0,
            ],
        ];

        foreach ($reviewsData as $reviewData) {
            // Random user and product
            $user = $users->random();
            $product = $products->random();

            Review::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'name' => $reviewData['name'],
                'email' => $reviewData['email'],
                'rating' => $reviewData['rating'],
                'content' => $reviewData['content'],
                'status' => $reviewData['status'],
                'created_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        $this->command->info('Đã tạo thành công ' . count($reviewsData) . ' reviews mẫu.');
    }
}
