<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Events\OrderStatusUpdated;
use Illuminate\Support\Facades\Log;

class AutoConfirmDeliveredOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;
    public $tries = 3; // Số lần thử lại nếu thất bại
    public $timeout = 60; // Timeout 60 giây

    /**
     * Create a new job instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Kiểm tra lại trạng thái hiện tại
            $order = Order::find($this->order->id);
            
            if (!$order) {
                Log::warning("Order not found for auto-confirm: {$this->order->id}");
                return;
            }

            // Kiểm tra xem đơn hàng có còn ở trạng thái delivered và chưa được xác nhận không
            if ($order->status !== 'delivered' || $order->is_received) {
                Log::info("Order #{$order->code_order} is no longer eligible for auto-confirm. Status: {$order->status}, is_received: " . ($order->is_received ? 'true' : 'false'));
                return;
            }

            // Lưu trạng thái cũ
            $oldStatus = $order->status;
            
            // Cập nhật trạng thái
            $order->status = 'received';
            $order->is_received = true;
            $order->save();

            // Gửi event realtime
            try {
                event(new OrderStatusUpdated($order, $oldStatus, $order->status));
                Log::info("Auto-confirmed delivered order #{$order->code_order} - realtime event sent");
            } catch (\Exception $e) {
                Log::warning("Failed to broadcast OrderStatusUpdated event for auto-confirmed order #{$order->code_order}: " . $e->getMessage());
            }

            // Ghi log thành công
            Log::info("Successfully auto-confirmed delivered order #{$order->code_order} (ID: {$order->id})", [
                'order_id' => $order->id,
                'order_code' => $order->code_order,
                'old_status' => $oldStatus,
                'new_status' => $order->status,
                'user_id' => $order->user_id,
                'auto_confirmed_at' => now()
            ]);

        } catch (\Exception $e) {
            Log::error("Error in AutoConfirmDeliveredOrderJob for order #{$this->order->code_order}: " . $e->getMessage(), [
                'order_id' => $this->order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Throw lại exception để job có thể retry
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("AutoConfirmDeliveredOrderJob failed for order #{$this->order->code_order}", [
            'order_id' => $this->order->id,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}
