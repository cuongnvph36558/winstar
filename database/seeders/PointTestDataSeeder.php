<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Point;
use App\Models\PointTransaction;
use App\Models\PointVoucher;
use App\Models\UserPointVoucher;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PointTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo user test nếu chưa có
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'User Test',
                'phone' => '0123456789',
                'address' => '123 Test Street',
                'password' => bcrypt('password'),
                'status' => 1,
            ]
        );

        // Tạo point record cho user
        $point = Point::firstOrCreate(
            ['user_id' => $user->id],
            [
                'total_points' => 500,
                'earned_points' => 800,
                'used_points' => 300,
                'expired_points' => 0,
                'vip_level' => 'Silver',
            ]
        );

        // Tạo một số giao dịch điểm mẫu
        $transactions = [
            [
                'user_id' => $user->id,
                'type' => 'earn',
                'points' => 200,
                'description' => 'Tích điểm từ đơn hàng #ORD001',
                'reference_type' => 'order',
                'reference_id' => 1,
                'expiry_date' => Carbon::now()->addMonths(12),
                'is_expired' => false,
                'created_at' => Carbon::now()->subDays(30),
            ],
            [
                'user_id' => $user->id,
                'type' => 'earn',
                'points' => 150,
                'description' => 'Tích điểm từ đơn hàng #ORD002',
                'reference_type' => 'order',
                'reference_id' => 2,
                'expiry_date' => Carbon::now()->addMonths(12),
                'is_expired' => false,
                'created_at' => Carbon::now()->subDays(20),
            ],
            [
                'user_id' => $user->id,
                'type' => 'use',
                'points' => -100,
                'description' => 'Đổi điểm lấy voucher: Giảm 50.000đ cho đơn hàng từ 500.000đ',
                'reference_type' => 'voucher',
                'reference_id' => 1,
                'created_at' => Carbon::now()->subDays(15),
            ],
            [
                'user_id' => $user->id,
                'type' => 'earn',
                'points' => 300,
                'description' => 'Tích điểm từ đơn hàng #ORD003',
                'reference_type' => 'order',
                'reference_id' => 3,
                'expiry_date' => Carbon::now()->addMonths(12),
                'is_expired' => false,
                'created_at' => Carbon::now()->subDays(10),
            ],
            [
                'user_id' => $user->id,
                'type' => 'use',
                'points' => -200,
                'description' => 'Đổi điểm lấy voucher: Giảm 10% cho đơn hàng từ 1.000.000đ',
                'reference_type' => 'voucher',
                'reference_id' => 2,
                'created_at' => Carbon::now()->subDays(5),
            ],
            [
                'user_id' => $user->id,
                'type' => 'bonus',
                'points' => 50,
                'description' => 'Điểm thưởng từ admin',
                'expiry_date' => Carbon::now()->addMonths(12),
                'is_expired' => false,
                'created_at' => Carbon::now()->subDays(2),
            ],
        ];

        foreach ($transactions as $transactionData) {
            PointTransaction::firstOrCreate(
                [
                    'user_id' => $transactionData['user_id'],
                    'type' => $transactionData['type'],
                    'points' => $transactionData['points'],
                    'description' => $transactionData['description'],
                    'created_at' => $transactionData['created_at'],
                ],
                $transactionData
            );
        }

        // Tạo một số user voucher mẫu
        $userVouchers = [
            [
                'user_id' => $user->id,
                'point_voucher_id' => 1,
                'voucher_code' => 'POINT' . strtoupper(substr(md5(uniqid()), 0, 8)),
                'status' => 'active',
                'expiry_date' => Carbon::now()->addMonths(3),
                'created_at' => Carbon::now()->subDays(15),
            ],
            [
                'user_id' => $user->id,
                'point_voucher_id' => 2,
                'voucher_code' => 'POINT' . strtoupper(substr(md5(uniqid()), 0, 8)),
                'status' => 'active',
                'expiry_date' => Carbon::now()->addMonths(3),
                'created_at' => Carbon::now()->subDays(5),
            ],
        ];

        foreach ($userVouchers as $userVoucherData) {
            UserPointVoucher::firstOrCreate(
                [
                    'user_id' => $userVoucherData['user_id'],
                    'point_voucher_id' => $userVoucherData['point_voucher_id'],
                    'voucher_code' => $userVoucherData['voucher_code'],
                ],
                $userVoucherData
            );
        }

        $this->command->info('Point test data seeded successfully!');
        $this->command->info('Test user: test@example.com / password');
    }
} 