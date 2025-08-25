<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Chuyển tất cả đơn hàng có trạng thái 'received' thành 'completed'
        DB::table('orders')
            ->where('status', 'received')
            ->update(['status' => 'completed']);

        // Loại bỏ 'received' khỏi enum
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'processing', 'shipping', 'delivered', 'completed', 'cancelled') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Thêm lại 'received' vào enum
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'processing', 'shipping', 'delivered', 'received', 'completed', 'cancelled') DEFAULT 'pending'");
    }
};
