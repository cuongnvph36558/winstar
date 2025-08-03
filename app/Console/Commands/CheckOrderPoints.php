<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\PointTransaction;

class CheckOrderPoints extends Command
{
    protected $signature = 'points:check-order {order_id}';
    protected $description = 'Kiểm tra điểm của đơn hàng cụ thể';

    public function handle()
    {
        $orderId = $this->argument('order_id');
        $order = Order::with('user')->find($orderId);

        if (!$order) {
            $this->error("Không tìm thấy đơn hàng ID: {$orderId}");
            return 1;
        }

        $this->info("Thông tin đơn hàng:");
        $this->info("ID: {$order->id}");
        $this->info("Code: {$order->code_order}");
        $this->info("User: {$order->user->name}");
        $this->info("Amount: " . number_format($order->total_amount));
        $this->info("Status: {$order->status}");
        $this->info("Points earned: " . ($order->points_earned ?? 'null'));
        $this->info("=====================================");

        // Kiểm tra giao dịch điểm
        $transactions = PointTransaction::where('user_id', $order->user_id)
            ->where('reference_type', 'order')
            ->where('reference_id', $order->id)
            ->get();

        if ($transactions->isEmpty()) {
            $this->info("Không có giao dịch điểm nào cho đơn hàng này.");
        } else {
            $this->info("Giao dịch điểm:");
            foreach ($transactions as $transaction) {
                $this->line("Type: {$transaction->type}, Points: {$transaction->points}, Description: {$transaction->description}, Created: {$transaction->created_at}");
            }
        }

        // Kiểm tra point record của user
        $point = $order->user->point;
        if ($point) {
            $this->info("=====================================");
            $this->info("Thông tin điểm của user:");
            $this->info("Tổng điểm: " . number_format($point->total_points));
            $this->info("Điểm đã tích: " . number_format($point->earned_points));
            $this->info("Điểm đã dùng: " . number_format($point->used_points));
            $this->info("Điểm đã hết hạn: " . number_format($point->expired_points));
        } else {
            $this->info("User chưa có point record.");
        }

        return 0;
    }
} 