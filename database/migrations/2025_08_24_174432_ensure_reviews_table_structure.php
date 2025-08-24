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
        Schema::table('reviews', function (Blueprint $table) {
            // Đảm bảo có trường order_id nếu chưa có
            if (!Schema::hasColumn('reviews', 'order_id')) {
                $table->unsignedBigInteger('order_id')->nullable()->after('product_id');
                $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
            }
            
            // Đảm bảo có trường status với default value
            if (!Schema::hasColumn('reviews', 'status')) {
                $table->integer('status')->default(0)->after('content');
            }
            
            // Đảm bảo có trường name và email
            if (!Schema::hasColumn('reviews', 'name')) {
                $table->string('name')->nullable()->after('order_id');
            }
            
            if (!Schema::hasColumn('reviews', 'email')) {
                $table->string('email')->nullable()->after('name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Chỉ drop các trường mới thêm nếu cần
            if (Schema::hasColumn('reviews', 'order_id')) {
                $table->dropForeign(['order_id']);
                $table->dropColumn('order_id');
            }
        });
    }
};
