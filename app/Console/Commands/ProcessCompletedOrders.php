<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Services\PointService;
use Illuminate\Support\Facades\Log;

class ProcessCompletedOrders extends Command
{
    protected $signature = 'points:process-completed-orders';
    protected $description = 'Tích điểm cho các đơn hàng đã hoàn thành nhưng chưa được tích điểm';

    protected $pointService;

    public function __construct(PointService $pointService)
    {
        parent::__construct();
        $this->pointService = $pointService;
    }

    public function handle()
    {
        $this->info('Bắt đầu xử lý tích điểm cho đơn hàng đã hoàn thành...');

        // Lấy các đơn hàng đã hoàn thành
        $completedOrders = Order::where('status', 'completed')
            ->with('user')
            ->get();

        $processedCount = 0;
        $errorCount = 0;

        foreach ($completedOrders as $order) {
            try {
                // Kiểm tra xem đã có giao dịch điểm cho đơn hàng này chưa
                $existingTransaction = \App\Models\PointTransaction::where('user_id', $order->user_id)
                    ->where('reference_type', 'order')
                    ->where('reference_id', $order->id)
                    ->first();

                if (!$existingTransaction) {
                    // Tích điểm cho đơn hàng
                    $result = $this->pointService->earnPointsFromOrder($order->user, $order);
                    
                    if ($result) {
                        $this->info("✅ Đã tích điểm cho đơn hàng #{$order->code_order}");
                        $processedCount++;
                    } else {
                        $this->error("❌ Lỗi tích điểm cho đơn hàng #{$order->code_order}");
                        $errorCount++;
                    }
                } else {
                    $this->line("⏭️  Đơn hàng #{$order->code_order} đã được tích điểm trước đó");
                }
            } catch (\Exception $e) {
                $this->error("❌ Lỗi xử lý đơn hàng #{$order->code_order}: " . $e->getMessage());
                $errorCount++;
                Log::error("Lỗi xử lý đơn hàng #{$order->code_order}: " . $e->getMessage());
            }
        }

        $this->info("Hoàn thành! Đã xử lý {$processedCount} đơn hàng, {$errorCount} lỗi.");
        
        return 0;
    }
} 