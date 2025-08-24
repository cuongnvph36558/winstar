<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Order;
use App\Models\PointTransaction;
use App\Models\User;
use App\Services\PointService;

echo "=== KIỂM TRA VẤN ĐỀ TÍCH ĐIỂM TỰ ĐỘNG ===\n\n";

// Kiểm tra user Cường Nguyễn
$user = User::where('name', 'Cường Nguyễn')->first();

if ($user) {
    echo "User: {$user->name} (ID: {$user->id})\n\n";
    
    // Lấy các đơn hàng completed gần đây
    $recentCompletedOrders = Order::where('user_id', $user->id)
        ->where('status', 'completed')
        ->orderBy('updated_at', 'desc')
        ->take(5)
        ->get();
    
    echo "=== 5 ĐƠN HÀNG COMPLETED GẦN ĐÂY ===\n";
    foreach ($recentCompletedOrders as $order) {
        echo "Đơn hàng #{$order->code_order}:\n";
        echo "  - Trạng thái: {$order->status}\n";
        echo "  - Tổng tiền: " . number_format($order->total_amount) . " VND\n";
        echo "  - points_earned: " . ($order->points_earned ?? 'NULL') . "\n";
        echo "  - Cập nhật lúc: {$order->updated_at}\n";
        
        // Kiểm tra giao dịch điểm
        $transaction = PointTransaction::where('user_id', $user->id)
            ->where('reference_type', 'order')
            ->where('reference_id', $order->id)
            ->where('type', 'earn')
            ->first();
            
        if ($transaction) {
            echo "  - Giao dịch điểm: Có (ID: {$transaction->id}, Points: " . number_format($transaction->points) . ")\n";
        } else {
            echo "  - Giao dịch điểm: Không có\n";
        }
        echo "\n";
    }
    
    // Kiểm tra logic tích điểm
    echo "=== KIỂM TRA LOGIC TÍCH ĐIỂM ===\n";
    $pointService = new PointService();
    
    foreach ($recentCompletedOrders as $order) {
        echo "Đơn hàng #{$order->code_order}:\n";
        
        // Kiểm tra điều kiện 1: points_earned
        if ($order->points_earned !== null && $order->points_earned > 0) {
            echo "  - Điều kiện 1: Đã tích điểm trước đó (points_earned = {$order->points_earned})\n";
        } else {
            echo "  - Điều kiện 1: Chưa tích điểm (points_earned = " . ($order->points_earned ?? 'NULL') . ")\n";
        }
        
        // Kiểm tra điều kiện 2: existing transaction
        $existingTransaction = PointTransaction::where('user_id', $user->id)
            ->where('reference_type', 'order')
            ->where('reference_id', $order->id)
            ->where('type', 'earn')
            ->first();
            
        if ($existingTransaction) {
            echo "  - Điều kiện 2: Đã có giao dịch điểm (ID: {$existingTransaction->id})\n";
        } else {
            echo "  - Điều kiện 2: Chưa có giao dịch điểm\n";
        }
        
        // Tính điểm sẽ được tích
        $pointsToEarn = $order->total_amount * 0.01; // 1%
        echo "  - Điểm sẽ được tích: " . number_format($pointsToEarn) . "\n";
        echo "\n";
    }
    
} else {
    echo "Không tìm thấy user Cường Nguyễn\n";
}
