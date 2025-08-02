<?php

namespace App\Console\Commands;

use App\Events\NewOrderPlaced;
use App\Models\Order;
use Illuminate\Console\Command;

class TestRealtimeOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:realtime-order {order_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test realtime order notification system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orderId = $this->argument('order_id');
        
        if ($orderId) {
            $order = Order::find($orderId);
            if (!$order) {
                $this->error("Order with ID {$orderId} not found!");
                return 1;
            }
        } else {
            // Lấy đơn hàng mới nhất
            $order = Order::latest()->first();
            if (!$order) {
                $this->error("No orders found in database!");
                return 1;
            }
        }

        $this->info("Testing realtime notification for order #{$order->code_order}");
        $this->info("Order ID: {$order->id}");
        $this->info("Customer: " . ($order->user ? $order->user->name : 'Guest'));
        $this->info("Total: " . number_format($order->total_amount, 0, ',', '.') . "₫");
        $this->info("Status: {$order->status}");

        // Trigger event
        event(new NewOrderPlaced($order));

        $this->info("✅ NewOrderPlaced event triggered successfully!");
        $this->info("📡 Check admin page to see realtime notification");
        
        return 0;
    }
} 