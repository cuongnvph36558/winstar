<?php

namespace App\Console\Commands;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckOnlinePaymentOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:check-online-payments {--dry-run : Chạy thử nghiệm không thực hiện thay đổi}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kiểm tra và cập nhật trạng thái đơn hàng đã thanh toán online quá 15 phút';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        
        if ($isDryRun) {
            $this->info('🔍 [DRY RUN] Kiểm tra đơn hàng đã thanh toán online quá 15 phút...');
        } else {
            $this->info('🔄 Kiểm tra đơn hàng đã thanh toán online quá 15 phút...');
        }

        // Tìm đơn hàng đã thanh toán online và đang ở trạng thái processing
        $orders = Order::where('payment_status', 'paid')
            ->where('status', 'processing')
            ->where('payment_method', 'vnpay')
            ->where('created_at', '<=', now()->subMinutes(15))
            ->get();

        $this->info("📊 Tìm thấy {$orders->count()} đơn hàng cần kiểm tra");

        if ($orders->isEmpty()) {
            $this->info('✅ Không có đơn hàng nào cần xử lý');
            return 0;
        }

        $processedCount = 0;
        $errorCount = 0;

        foreach ($orders as $order) {
            try {
                $this->info("🔄 Xử lý đơn hàng #{$order->code_order} (ID: {$order->id})");
                $this->info("   - Thời gian tạo: {$order->created_at->format('d/m/Y H:i:s')}");
                $this->info("   - Đã qua: " . $order->created_at->diffForHumans());

                if (!$isDryRun) {
                    // Cập nhật trạng thái đơn hàng - chuyển từ processing sang shipping
                    // hoặc giữ nguyên processing tùy theo logic nghiệp vụ
                    $oldStatus = $order->status;
                    
                    // Logic: Nếu đơn hàng đã thanh toán và đang processing quá 15 phút
                    // thì có thể chuyển sang trạng thái shipping (đang giao hàng)
                    // hoặc giữ nguyên processing để admin xử lý thủ công
                    
                    // Ở đây chúng ta sẽ giữ nguyên trạng thái processing
                    // và chỉ ghi log để admin biết
                    
                    Log::info("Online payment order #{$order->code_order} has been processing for more than 15 minutes", [
                        'order_id' => $order->id,
                        'order_code' => $order->code_order,
                        'status' => $order->status,
                        'payment_status' => $order->payment_status,
                        'created_at' => $order->created_at,
                        'processing_duration_minutes' => $order->created_at->diffInMinutes(now()),
                        'user_id' => $order->user_id
                    ]);

                    $this->info("   ✅ Đã ghi log cho đơn hàng đã xử lý quá 15 phút");
                } else {
                    $this->info("   🔍 [DRY RUN] Sẽ ghi log cho đơn hàng đã xử lý quá 15 phút");
                }

                $processedCount++;

            } catch (\Exception $e) {
                $errorCount++;
                $this->error("   ❌ Lỗi xử lý đơn hàng #{$order->code_order}: " . $e->getMessage());
                Log::error("Failed to process online payment order #{$order->code_order}", [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        $this->info('');
        $this->info('📈 Kết quả xử lý:');
        $this->info("   ✅ Đã xử lý: {$processedCount} đơn hàng");
        $this->info("   ❌ Lỗi: {$errorCount} đơn hàng");

        if ($isDryRun) {
            $this->info('');
            $this->info('💡 Để thực hiện thực tế, chạy lệnh: php artisan orders:check-online-payments');
        }

        return 0;
    }
}
