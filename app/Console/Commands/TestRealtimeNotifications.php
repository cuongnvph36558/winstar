<?php

namespace App\Console\Commands;

use App\Events\NewOrderPlaced;
use App\Events\OrderStatusUpdated;
use App\Models\Order;
use Illuminate\Console\Command;

class TestRealtimeNotifications extends Command
{
    protected $signature = 'test:notifications {type=order} {order_id?}';
    protected $description = 'test realtime notifications with modern UI';

    public function handle()
    {
        $type = $this->argument('type');
        $orderId = $this->argument('order_id');
        
        // Get order
        if ($orderId) {
            $order = Order::find($orderId);
            if (!$order) {
                $this->error("order with id {$orderId} not found!");
                return 1;
            }
        } else {
            $order = Order::latest()->first();
            if (!$order) {
                $this->error("no orders found in database!");
                return 1;
            }
        }

        $this->info("testing {$type} notification for order #{$order->code_order}");

        switch ($type) {
            case 'new-order':
                $this->testNewOrder($order);
                break;
            case 'status-update':
                $this->testStatusUpdate($order);
                break;
            case 'order':
            default:
                $this->testNewOrder($order);
                $this->info("waiting 3 seconds...");
                sleep(3);
                $this->testStatusUpdate($order);
                break;
        }

        $this->info("✅ notification test completed!");
        $this->info("📱 check your browser for realtime notifications");
        
        return 0;
    }

    private function testNewOrder($order)
    {
        $this->info("🎉 triggering new order notification...");
        try {
            event(new NewOrderPlaced($order));
        } catch (\Exception $e) {
            \Log::warning('Failed to broadcast NewOrderPlaced event: ' . $e->getMessage());
        }
        $this->info("✅ new order event sent");
    }

    private function testStatusUpdate($order)
    {
        $this->info("📦 triggering status update notification...");
        
        // Test different status updates
        $statuses = ['processing', 'shipping', 'completed'];
        $oldStatus = $order->status;
        
        foreach ($statuses as $newStatus) {
            $this->info("   updating status: {$oldStatus} → {$newStatus}");
            try {
            event(new OrderStatusUpdated($order, $oldStatus, $newStatus));
        } catch (\Exception $e) {
            \Log::warning('Failed to broadcast OrderStatusUpdated event: ' . $e->getMessage());
        }
            $oldStatus = $newStatus;
            sleep(1);
        }
        
        $this->info("✅ status update events sent");
    }
} 