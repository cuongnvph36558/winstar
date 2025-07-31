<?php

echo "=== SỬA CẤU HÌNH .ENV CHO REALTIME NOTIFICATIONS ===\n\n";

// Đọc file .env hiện tại
$envFile = '.env';
if (!file_exists($envFile)) {
    echo "❌ File .env không tồn tại!\n";
    exit(1);
}

$envContent = file_get_contents($envFile);
$lines = explode("\n", $envContent);

// Cấu hình cần thêm/cập nhật
$configs = [
    'BROADCAST_DRIVER' => 'pusher',
    'PUSHER_APP_ID' => 'local-app',
    'PUSHER_APP_KEY' => 'localkey123',
    'PUSHER_APP_SECRET' => 'localsecret123',
    'PUSHER_HOST' => '127.0.0.1',
    'PUSHER_PORT' => '6001',
    'PUSHER_SCHEME' => 'http',
    'PUSHER_APP_CLUSTER' => 'mt1',
    'QUEUE_CONNECTION' => 'database'
];

$updated = false;
$newLines = [];

foreach ($lines as $line) {
    $line = trim($line);
    
    // Bỏ qua dòng trống và comment
    if (empty($line) || strpos($line, '#') === 0) {
        $newLines[] = $line;
        continue;
    }
    
    // Kiểm tra xem có phải là cấu hình broadcasting không
    $key = null;
    foreach ($configs as $configKey => $configValue) {
        if (strpos($line, $configKey . '=') === 0) {
            $key = $configKey;
            break;
        }
    }
    
    if ($key) {
        // Cập nhật giá trị
        $newLines[] = $key . '=' . $configs[$key];
        echo "✅ Cập nhật: {$key} = {$configs[$key]}\n";
        $updated = true;
        unset($configs[$key]); // Đánh dấu đã xử lý
    } else {
        $newLines[] = $line;
    }
}

// Thêm các cấu hình mới
if (!empty($configs)) {
    $newLines[] = "\n# Broadcasting Configuration";
    foreach ($configs as $key => $value) {
        $newLines[] = $key . '=' . $value;
        echo "✅ Thêm mới: {$key} = {$value}\n";
        $updated = true;
    }
}

// Ghi lại file .env
if ($updated) {
    file_put_contents($envFile, implode("\n", $newLines));
    echo "\n✅ Đã cập nhật file .env thành công!\n";
} else {
    echo "\nℹ️ File .env đã có đầy đủ cấu hình cần thiết.\n";
}

echo "\n=== BƯỚC TIẾP THEO ===\n";
echo "1. Chạy: php artisan config:clear\n";
echo "2. Chạy: php artisan config:cache\n";
echo "3. Chạy: php artisan websockets:serve (terminal 1)\n";
echo "4. Chạy: php artisan queue:work (terminal 2)\n";
echo "5. Chạy: php artisan test:broadcast\n";
echo "6. Mở trang web và kiểm tra console\n"; 