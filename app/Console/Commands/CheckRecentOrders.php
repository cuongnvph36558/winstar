<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;

class CheckRecentOrders extends Command
{
    protected $signature = 'orders:check-recent {limit=10}';
    protected $description = 'Kiểm tra đơn hàng gần đây';

    public function handle()
    {
        $limit = $this->argument('limit');
        
        $this->info("Kiểm tra {$limit} đơn hàng gần đây:");
        $this->info("=====================================");

        $orders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get(['id', 'code_order', 'user_id', 'total_amount', 'status', 'points_earned', 'created_at']);

        if ($orders->isEmpty()) {
            $this->info("Không có đơn hàng nào.");
            return 0;
        }

        foreach ($orders as $order) {
            $this->line("ID: {$order->id}, Code: {$order->code_order}, User: {$order->user->name}, Amount: " . number_format($order->total_amount) . ", Status: {$order->status}, Points: " . ($order->points_earned ?? 'null') . ", Date: {$order->created_at}");
        }

        return 0;
    }
} 