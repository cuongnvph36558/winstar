<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact;
use App\Models\User;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        // Get some users for sample data
        $users = User::take(5)->get();
        
        if ($users->isEmpty()) {
            // Create a sample user if none exist
            $user = User::create([
                'name' => 'Nguyễn Văn A',
                'email' => 'nguyenvana@example.com',
                'password' => bcrypt('password'),
            ]);
            $users = collect([$user]);
        }

        // Sample contact data
        $contacts = [
            [
                'user_id' => $users->first()->id,
                'subject' => 'Hỏi về sản phẩm mới',
                'message' => 'Tôi muốn biết thêm thông tin về sản phẩm mới của các bạn. Có thể cho tôi biết giá cả và thời gian giao hàng không?',
                'reply' => 'Cảm ơn bạn đã quan tâm đến sản phẩm của chúng tôi. Sản phẩm mới có giá 500,000 VNĐ và thời gian giao hàng từ 2-3 ngày làm việc. Bạn có thể đặt hàng trực tiếp trên website hoặc liên hệ hotline để được hỗ trợ.',
                'status' => 'resolved',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(1),
            ],
            [
                'user_id' => $users->first()->id,
                'subject' => 'Khiếu nại về dịch vụ',
                'message' => 'Tôi đã đặt hàng từ 1 tuần trước nhưng vẫn chưa nhận được. Đơn hàng số #12345. Mong các bạn kiểm tra và phản hồi sớm.',
                'reply' => 'Chúng tôi xin lỗi vì sự bất tiện này. Chúng tôi đã kiểm tra và thấy đơn hàng #12345 đang trong quá trình xử lý. Dự kiến sẽ giao hàng trong ngày mai. Chúng tôi sẽ liên hệ lại để xác nhận thời gian giao hàng cụ thể.',
                'status' => 'resolved',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(4),
            ],
            [
                'user_id' => $users->first()->id,
                'subject' => 'Đề xuất cải thiện website',
                'message' => 'Website của các bạn rất đẹp nhưng tôi thấy có thể cải thiện thêm phần tìm kiếm sản phẩm. Hiện tại việc lọc theo giá và danh mục chưa thực sự thuận tiện.',
                'status' => 'pending',
                'created_at' => now()->subHours(3),
                'updated_at' => now()->subHours(3),
            ],
            [
                'user_id' => null, // Guest user
                'subject' => 'Hỏi về chính sách đổi trả',
                'message' => 'Tôi muốn biết chính sách đổi trả hàng của các bạn như thế nào? Tôi có thể đổi hàng trong bao lâu và có mất phí không?',
                'reply' => 'Cảm ơn bạn đã quan tâm. Chính sách đổi trả của chúng tôi như sau: - Thời gian đổi trả: 30 ngày kể từ ngày nhận hàng - Điều kiện: Sản phẩm còn nguyên vẹn, chưa sử dụng - Phí đổi trả: Miễn phí nếu lỗi từ phía chúng tôi, 50,000 VNĐ nếu đổi do lý do cá nhân',
                'status' => 'resolved',
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subHours(12),
            ],
            [
                'user_id' => $users->count() > 1 ? $users[1]->id : $users->first()->id,
                'subject' => 'Đăng ký nhận thông báo khuyến mãi',
                'message' => 'Tôi muốn đăng ký nhận thông báo về các chương trình khuyến mãi và sản phẩm mới. Làm thế nào để đăng ký?',
                'status' => 'pending',
                'created_at' => now()->subHours(1),
                'updated_at' => now()->subHours(1),
            ],
        ];

        foreach ($contacts as $contact) {
            Contact::create($contact);
        }

        $this->command->info('Sample contact data created successfully!');
    }
} 