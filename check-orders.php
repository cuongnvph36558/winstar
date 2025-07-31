<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 Checking Orders in Database...\n\n";

try {
    $orders = \App\Models\Order::all();
    
    if ($orders->count() > 0) {
        echo "Found {$orders->count()} orders:\n";
        echo str_repeat('-', 80) . "\n";
        
        foreach ($orders as $order) {
            echo "ID: {$order->id}\n";
            echo "Code: " . ($order->code_order ?? 'NULL') . "\n";
            echo "Status: {$order->status}\n";
            echo "User ID: {$order->user_id}\n";
            echo "User Name: " . ($order->user->name ?? 'Unknown') . "\n";
            echo "Total: {$order->total_amount}\n";
            echo str_repeat('-', 40) . "\n";
        }
    } else {
        echo "❌ No orders found in database\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n🎯 Check completed!\n";
?> 