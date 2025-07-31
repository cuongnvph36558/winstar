<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Config;

echo "=== KIỂM TRA HỆ THỐNG THÔNG BÁO ===\n\n";

// 1. Kiểm tra cấu hình broadcasting
echo "1. Cấu hình Broadcasting:\n";
echo "   - BROADCAST_DRIVER: " . env('BROADCAST_DRIVER', 'NOT SET') . "\n";
echo "   - PUSHER_APP_ID: " . env('PUSHER_APP_ID', 'NOT SET') . "\n";
echo "   - PUSHER_APP_KEY: " . env('PUSHER_APP_KEY', 'NOT SET') . "\n";
echo "   - PUSHER_HOST: " . env('PUSHER_HOST', 'NOT SET') . "\n";
echo "   - PUSHER_PORT: " . env('PUSHER_PORT', 'NOT SET') . "\n";
echo "   - QUEUE_CONNECTION: " . env('QUEUE_CONNECTION', 'NOT SET') . "\n\n";

// 2. Kiểm tra database connection
try {
    $pdo = new PDO(
        'mysql:host=' . env('DB_HOST', '127.0.0.1') . 
        ';dbname=' . env('DB_DATABASE', 'winstar') . 
        ';port=' . env('DB_PORT', '3306'),
        env('DB_USERNAME', 'root'),
        env('DB_PASSWORD', '')
    );
    echo "2. Database Connection: ✅ Kết nối thành công\n\n";
} catch (Exception $e) {
    echo "2. Database Connection: ❌ Lỗi kết nối: " . $e->getMessage() . "\n\n";
}

// 3. Kiểm tra queue table
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'jobs'");
    if ($stmt->rowCount() > 0) {
        echo "3. Queue Table: ✅ Bảng 'jobs' tồn tại\n\n";
    } else {
        echo "3. Queue Table: ❌ Bảng 'jobs' không tồn tại\n\n";
    }
} catch (Exception $e) {
    echo "3. Queue Table: ❌ Lỗi kiểm tra: " . $e->getMessage() . "\n\n";
}

// 4. Kiểm tra websockets table
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'websockets_statistics_entries'");
    if ($stmt->rowCount() > 0) {
        echo "4. WebSockets Table: ✅ Bảng 'websockets_statistics_entries' tồn tại\n\n";
    } else {
        echo "4. WebSockets Table: ❌ Bảng 'websockets_statistics_entries' không tồn tại\n\n";
    }
} catch (Exception $e) {
    echo "4. WebSockets Table: ❌ Lỗi kiểm tra: " . $e->getMessage() . "\n\n";
}

// 5. Kiểm tra users và products
try {
    $stmt = $pdo->query("SELECT COUNT(*) as user_count FROM users");
    $userCount = $stmt->fetch()['user_count'];
    echo "5. Users: ✅ Có {$userCount} users\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as product_count FROM products");
    $productCount = $stmt->fetch()['product_count'];
    echo "   Products: ✅ Có {$productCount} products\n\n";
} catch (Exception $e) {
    echo "5. Users/Products: ❌ Lỗi kiểm tra: " . $e->getMessage() . "\n\n";
}

// 6. Kiểm tra port 6001
$connection = @fsockopen('127.0.0.1', 6001, $errno, $errstr, 5);
if (is_resource($connection)) {
    echo "6. WebSockets Server: ✅ Port 6001 đang mở\n\n";
    fclose($connection);
} else {
    echo "6. WebSockets Server: ❌ Port 6001 không mở (Lỗi: $errstr)\n\n";
}

echo "=== KẾT QUẢ KIỂM TRA ===\n";
echo "Nếu có lỗi, hãy chạy các lệnh sau:\n";
echo "1. php artisan config:clear\n";
echo "2. php artisan migrate\n";
echo "3. php artisan websockets:serve (trong terminal riêng)\n";
echo "4. php artisan queue:work (trong terminal riêng)\n";
echo "5. php artisan test:broadcast\n"; 