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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('return_video')->nullable()->after('admin_return_note'); // Video bóc hàng
            $table->string('return_order_image')->nullable()->after('return_video'); // Ảnh đơn hàng
            $table->string('return_product_image')->nullable()->after('return_order_image'); // Ảnh sản phẩm
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['return_video', 'return_order_image', 'return_product_image']);
        });
    }
};
