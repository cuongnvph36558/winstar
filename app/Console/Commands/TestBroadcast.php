<?php

namespace App\Console\Commands;

use App\Events\FavoriteUpdated;
use App\Events\CardUpdate;
use App\Events\OrderStatusUpdated;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Console\Command;

class TestBroadcast extends Command
{
    protected $signature = 'test:broadcast {type=favorite}';
    protected $description = 'Test broadcasting events';

    public function handle()
    {
        $type = $this->argument('type');
        
        $this->info("Testing {$type} broadcast...");
        
        switch ($type) {
            case 'favorite':
                $this->testFavoriteBroadcast();
                break;
            case 'cart':
                $this->testCartBroadcast();
                break;
            case 'order':
                $this->testOrderBroadcast();
                break;
            default:
                $this->error("Unknown type: {$type}");
                return 1;
        }
        
        $this->info("✅ {$type} broadcast test completed!");
        return 0;
    }
    
    private function testFavoriteBroadcast()
    {
        $user = User::first();
        $product = Product::first();
        
        if (!$user || !$product) {
            $this->error("No user or product found in database");
            return;
        }
        
        $this->info("Broadcasting FavoriteUpdated event...");
        broadcast(new FavoriteUpdated($user, $product, 'added', 5));
        $this->info("Event sent for user: {$user->name}, product: {$product->name}");
    }
    
    private function testCartBroadcast()
    {
        $user = User::first();
        $product = Product::first();
        
        if (!$user || !$product) {
            $this->error("No user or product found in database");
            return;
        }
        
        $this->info("Broadcasting CardUpdate event...");
        broadcast(new CardUpdate($user, $product, 'added', 3));
        $this->info("Event sent for user: {$user->name}, product: {$product->name}");
    }
    
    private function testOrderBroadcast()
    {
        $order = Order::first();
        
        if (!$order) {
            $this->error("No order found in database");
            return;
        }
        
        $this->info("Broadcasting OrderStatusUpdated event...");
        broadcast(new OrderStatusUpdated($order, 'pending', 'processing'));
        $this->info("Event sent for order: {$order->order_code}");
    }
} 