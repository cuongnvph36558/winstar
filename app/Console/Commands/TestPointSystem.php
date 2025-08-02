<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Order;
use App\Services\PointService;
use Carbon\Carbon;

class TestPointSystem extends Command
{
    protected $signature = 'points:test {--user-id=} {--order-id=}';
    protected $description = 'Test hệ thống tích điểm';

    protected $pointService;

    public function __construct(PointService $pointService)
    {
        parent::__construct();
        $this->pointService = $pointService;
    }

    public function handle()
    {
        $this->info('🧪 Bắt đầu test hệ thống tích điểm...');

        // Test 1: Kiểm tra user
        $userId = $this->option('user-id');
        $orderId = $this->option('order-id');

        if (!$userId) {
            $user = User::first();
            if (!$user) {
                $this->error('Không tìm thấy user nào trong hệ thống!');
                return 1;
            }
            $userId = $user->id;
        }

        $user = User::find($userId);
        if (!$user) {
            $this->error("Không tìm thấy user với ID: {$userId}");
            return 1;
        }

        $this->info("✅ User: {$user->name} (ID: {$user->id})");

        // Test 2: Kiểm tra điểm hiện tại
        $currentPoints = $user->getCurrentPoints();
        $vipLevel = $user->getVipLevel();
        $this->info("📊 Điểm hiện tại: {$currentPoints}");
        $this->info("🏆 Level VIP: {$vipLevel}");

        // Test 3: Kiểm tra đơn hàng
        if ($orderId) {
            $order = Order::find($orderId);
            if (!$order) {
                $this->error("Không tìm thấy đơn hàng với ID: {$orderId}");
                return 1;
            }
        } else {
            $order = $user->orders()->where('status', 'completed')->first();
            if (!$order) {
                $this->warn('Không tìm thấy đơn hàng hoàn thành để test!');
                $this->info('Tạo đơn hàng test...');

                // Tạo đơn hàng test
                $order = Order::create([
                    'user_id' => $user->id,
                    'code_order' => 'TEST' . time(),
                    'receiver_name' => $user->name,
                    'billing_city' => 'Hà Nội',
                    'billing_district' => 'Cầu Giấy',
                    'billing_ward' => 'Dịch Vọng',
                    'billing_address' => '123 Test Street',
                    'phone' => '0123456789',
                    'total_amount' => 1000000, // 1 triệu VND
                    'payment_method' => 'cod',
                    'status' => 'completed',
                    'payment_status' => 'paid',
                ]);

                $this->info("✅ Đã tạo đơn hàng test: {$order->code_order}");
            }
        }

        $this->info("📦 Đơn hàng: {$order->code_order} - {$order->total_amount} VND");

        // Test 4: Tính điểm dự kiến
        $expectedPoints = (int) ($order->total_amount * 0.05); // Bronze level
        $this->info("💰 Điểm dự kiến: {$expectedPoints} (5% của {$order->total_amount})");

        // Test 5: Tích điểm
        $this->info('🔄 Đang tích điểm...');
        $result = $this->pointService->earnPointsFromOrder($user, $order);

        if ($result) {
            $this->info('✅ Tích điểm thành công!');

            // Kiểm tra điểm sau khi tích
            $newPoints = $user->fresh()->getCurrentPoints();
            $this->info("📊 Điểm sau khi tích: {$newPoints}");
            $this->info("📈 Tăng: " . ($newPoints - $currentPoints) . " điểm");
        } else {
            $this->error('❌ Tích điểm thất bại!');
        }

        // Test 6: Kiểm tra lịch sử giao dịch
        $transactions = $user->pointTransactions()->latest()->take(5)->get();
        $this->info('📋 Lịch sử giao dịch gần đây:');
        foreach ($transactions as $transaction) {
            $this->line("  - {$transaction->created_at->format('d/m/Y H:i')}: {$transaction->points} điểm - {$transaction->description}");
        }

        // Test 7: Thống kê điểm
        $stats = $this->pointService->getUserPointStats($user);
        $this->info('📊 Thống kê điểm:');
        $this->line("  - Tổng điểm: {$stats['total_points']}");
        $this->line("  - Điểm đã tích: {$stats['earned_points']}");
        $this->line("  - Điểm đã dùng: {$stats['used_points']}");
        $this->line("  - Điểm hết hạn: {$stats['expired_points']}");
        $this->line("  - Level VIP: {$stats['vip_level']}");
        $this->line("  - Tỷ lệ tích điểm: {$stats['point_rate']}");

        $this->info('🎉 Test hệ thống tích điểm hoàn thành!');
        return 0;
    }
}
