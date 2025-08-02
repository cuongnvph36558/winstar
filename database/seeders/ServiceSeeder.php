<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'icon' => 'icon-basket',
                'title' => 'Mua sắm trực tuyến',
                'description' => 'Trải nghiệm mua sắm tiện lợi, dễ dàng với giao diện thân thiện và quy trình đơn giản.',
                'order' => 1,
            ],
            [
                'icon' => 'icon-bike',
                'title' => 'Giao hàng tận nơi',
                'description' => 'Dịch vụ giao hàng nhanh chóng, đảm bảo sản phẩm đến tay khách hàng trong thời gian sớm nhất.',
                'order' => 2,
            ],
            [
                'icon' => 'icon-tools',
                'title' => 'Bảo hành sản phẩm',
                'description' => 'Chế độ bảo hành toàn diện, đổi trả linh hoạt đảm bảo quyền lợi tốt nhất cho khách hàng.',
                'order' => 3,
            ],
            [
                'icon' => 'icon-genius',
                'title' => 'Tư vấn chuyên nghiệp',
                'description' => 'Đội ngũ tư vấn viên giàu kinh nghiệm, hỗ trợ khách hàng chọn lựa sản phẩm phù hợp nhất.',
                'order' => 4,
            ],
            [
                'icon' => 'icon-mobile',
                'title' => 'Ứng dụng di động',
                'description' => 'Mua sắm mọi lúc mọi nơi với ứng dụng di động tiện lợi, tối ưu trải nghiệm người dùng.',
                'order' => 5,
            ],
            [
                'icon' => 'icon-lifesaver',
                'title' => 'Chăm sóc khách hàng',
                'description' => 'Dịch vụ chăm sóc khách hàng tận tâm, giải đáp mọi thắc mắc và hỗ trợ kịp thời.',
                'order' => 6,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
} 