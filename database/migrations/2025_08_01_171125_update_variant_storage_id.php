<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Lấy storage 128GB (hoặc tạo mới nếu chưa có)
        $storage = \App\Models\Storage::firstOrCreate(
            ['capacity' => '128GB'],
            ['capacity' => '128GB']
        );
        
        // Cập nhật variant có ID 60 với storage_id
        \App\Models\ProductVariant::where('id', 60)->update([
            'storage_id' => $storage->id
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Xóa storage_id cho variant có ID 60
        \App\Models\ProductVariant::where('id', 60)->update([
            'storage_id' => null
        ]);
    }
};
