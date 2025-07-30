<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Lấy danh sách tất cả migration files
$migrationFiles = glob('database/migrations/*.php');
$batch = 1;

foreach ($migrationFiles as $file) {
    $filename = basename($file, '.php');
    
    // Kiểm tra xem migration đã tồn tại chưa
    $exists = DB::table('migrations')->where('migration', $filename)->exists();
    
    if (!$exists) {
        DB::table('migrations')->insert([
            'migration' => $filename,
            'batch' => $batch
        ]);
        echo "Added migration: $filename\n";
    } else {
        echo "Migration already exists: $filename\n";
    }
}

echo "Migration table fixed successfully!\n"; 