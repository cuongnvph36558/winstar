<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Events\OrderStatusUpdated;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FixDeliveredOrdersPaymentStatus extends Command
{
    protected $signature = 'orders:fix-payment-status {--dry-run : Chạy thử nghiệm không thay đổi database}';
    protected $description = 'Sửa trạng thái thanh toán cho các đơn hàng đã giao nhưng chưa cập nhật trạng thái thanh toán';

    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        
        if ($isDryRun) {
            $this->info('🔍 Chạy thử nghiệm - không thay đổi database');
        } else {
            $this->info('🚀 Bắt đầu sửa trạng thái thanh toán cho đơn hàng đã giao...');
        }

        // Tìm các đơn hàng có trạng thái "delivered" nhưng payment_status chưa phải "paid"
        $orders = Order::where('status', 'delivered')
            ->where('payment_status', '!=', 'paid')
            ->get();

        $this->info("📦 Tìm thấy {$orders->count()} đơn hàng cần sửa trạng thái thanh toán");

        if ($orders->isEmpty()) {
            $this->info('✅ Không có đơn hàng nào cần sửa');
            return 0;
        }

        $processedCount = 0;
        $errorCount = 0;

        foreach ($orders as $order) {
            try {
                $this->info("🔄 Xử lý đơn hàng #{$order->code_order} (ID: {$order->id})");
                $this->info("   - Trạng thái đơn hàng: {$order->status}");
                $this->info("   - Trạng thái thanh toán hiện tại: {$order->payment_status}");
                $this->info("   - Thời gian cập nhật cuối: {$order->updated_at->format('d/m/Y H:i:s')}");

                if (!$isDryRun) {
                    $oldPaymentStatus = $order->payment_status;
                    
                    // Cập nhật trạng thái thanh toán
                    $order->payment_status = 'paid';
                    $order->save();

                    // Gửi event realtime để cập nhật frontend
                    try {
                        event(new OrderStatusUpdated($order, $order->status, $order->status));
                        $this->info("   ✅ Đã gửi event realtime");
                    } catch (\Exception $e) {
                        $this->warn("   ⚠️ Không thể gửi event realtime: " . $e->getMessage());
                        Log::warning("Failed to broadcast OrderStatusUpdated event for order #{$order->code_order}: " . $e->getMessage());
                    }

                    // Ghi log
                    Log::info("Fixed payment status for delivered order #{$order->code_order}", [
                        'order_id' => $order->id,
                        'order_code' => $order->code_order,
                        'old_payment_status' => $oldPaymentStatus,
                        'new_payment_status' => $order->payment_status,
                        'order_status' => $order->status,
                        'user_id' => $order->user_id,
                        'fixed_at' => Carbon::now()
                    ]);

                    $processedCount++;
                    $this->info("   ✅ Đã sửa trạng thái thanh toán từ '{$oldPaymentStatus}' sang 'paid'");
                } else {
                    $this->info("   🔍 [DRY RUN] Sẽ sửa trạng thái thanh toán từ '{$order->payment_status}' sang 'paid'");
                    $processedCount++;
                }

            } catch (\Exception $e) {
                $errorCount++;
                $this->error("   ❌ Lỗi xử lý đơn hàng #{$order->code_order}: " . $e->getMessage());
                Log::error("Error fixing payment status for order #{$order->code_order}: " . $e->getMessage(), [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        // Tóm tắt kết quả
        $this->newLine();
        $this->info('📊 Tóm tắt kết quả:');
        $this->info("   - Tổng đơn hàng tìm thấy: {$orders->count()}");
        $this->info("   - Xử lý thành công: {$processedCount}");
        $this->info("   - Lỗi: {$errorCount}");

        if ($isDryRun) {
            $this->info('🔍 Đây là chạy thử nghiệm - không có thay đổi nào được thực hiện');
        } else {
            $this->info('✅ Hoàn thành sửa trạng thái thanh toán cho đơn hàng đã giao');
        }

        return 0;
    }
}
