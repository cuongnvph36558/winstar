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
        Schema::table('order_details', function (Blueprint $table) {
            // Thêm các field để lưu trữ thông tin variant gốc
            $table->string('original_variant_name')->nullable()->after('product_name');
            $table->string('original_color_name')->nullable()->after('original_variant_name');
            $table->string('original_storage_name')->nullable()->after('original_color_name');
            $table->string('original_storage_capacity')->nullable()->after('original_storage_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn([
                'original_variant_name',
                'original_color_name', 
                'original_storage_name',
                'original_storage_capacity'
            ]);
        });
    }
};
