<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Events\OrderStatusUpdated;
use App\Jobs\AutoConfirmDeliveredOrderJob;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AutoConfirmDeliveredOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:auto-confirm-delivered {--dry-run : Chạy thử nghiệm không thay đổi database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tự động chuyển trạng thái đơn hàng từ "delivered" sang "received" sau 1 ngày';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        
        if ($isDryRun) {
            $this->info('🔍 Chạy thử nghiệm - không thay đổi database');
        } else {
            $this->info('🚀 Bắt đầu tự động chuyển trạng thái đơn hàng...');
        }

        // Tìm các đơn hàng có trạng thái "delivered" và đã được giao hơn 1 ngày
        $orders = Order::where('status', 'delivered')
            ->where('is_received', false)
            ->where('updated_at', '<=', Carbon::now()->subDay())
            ->get();

        $this->info("📦 Tìm thấy {$orders->count()} đơn hàng cần chuyển trạng thái");

        if ($orders->isEmpty()) {
            $this->info('✅ Không có đơn hàng nào cần xử lý');
            return 0;
        }

        $processedCount = 0;
        $errorCount = 0;

        foreach ($orders as $order) {
            try {
                $this->info("🔄 Xử lý đơn hàng #{$order->code_order} (ID: {$order->id})");
                $this->info("   - Trạng thái hiện tại: {$order->status}");
                $this->info("   - Thời gian cập nhật cuối: {$order->updated_at->format('d/m/Y H:i:s')}");
                $this->info("   - Đã qua: " . $order->updated_at->diffForHumans());

                if (!$isDryRun) {
                    // Sử dụng Job để xử lý async (tùy chọn)
                    // AutoConfirmDeliveredOrderJob::dispatch($order);
                    
                    // Hoặc xử lý trực tiếp
                    $oldStatus = $order->status;
                    
                    // Cập nhật trạng thái
                    $order->status = 'received';
                    $order->is_received = true;
                    
                    // Cập nhật trạng thái thanh toán nếu chưa thanh toán
                    if ($order->payment_status !== 'paid') {
                        $order->payment_status = 'paid';
                        $this->info("   💰 Đã cập nhật trạng thái thanh toán thành 'paid'");
                    }
                    
                    $order->save();

                    // Gửi event realtime
                    try {
                        event(new OrderStatusUpdated($order, $oldStatus, $order->status));
                        $this->info("   ✅ Đã gửi event realtime");
                    } catch (\Exception $e) {
                        $this->warn("   ⚠️ Không thể gửi event realtime: " . $e->getMessage());
                        Log::warning("Failed to broadcast OrderStatusUpdated event for order #{$order->code_order}: " . $e->getMessage());
                    }

                    // Ghi log
                    Log::info("Auto-confirmed delivered order #{$order->code_order} (ID: {$order->id}) after 1 day", [
                        'order_id' => $order->id,
                        'order_code' => $order->code_order,
                        'old_status' => $oldStatus,
                        'new_status' => $order->status,
                        'user_id' => $order->user_id,
                        'delivered_at' => $order->updated_at,
                        'confirmed_at' => Carbon::now()
                    ]);

                    $processedCount++;
                    $this->info("   ✅ Đã chuyển trạng thái thành công");
                } else {
                    $this->info("   🔍 [DRY RUN] Sẽ chuyển từ '{$order->status}' sang 'received'");
                    $processedCount++;
                }

            } catch (\Exception $e) {
                $errorCount++;
                $this->error("   ❌ Lỗi xử lý đơn hàng #{$order->code_order}: " . $e->getMessage());
                Log::error("Error auto-confirming delivered order #{$order->code_order}: " . $e->getMessage(), [
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
            $this->info('✅ Hoàn thành tự động chuyển trạng thái đơn hàng');
        }

        return 0;
    }
}
