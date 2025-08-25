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
            // Thêm các trường cho việc chọn sản phẩm hoàn hàng
            $table->boolean('is_returned')->default(false)->after('product_name');
            $table->integer('return_quantity')->default(0)->after('is_returned');
            $table->text('return_reason')->nullable()->after('return_quantity');
            $table->decimal('return_amount', 15, 2)->nullable()->after('return_reason');
            $table->timestamp('returned_at')->nullable()->after('return_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn([
                'is_returned',
                'return_quantity',
                'return_reason',
                'return_amount',
                'returned_at'
            ]);
        });
    }
};
