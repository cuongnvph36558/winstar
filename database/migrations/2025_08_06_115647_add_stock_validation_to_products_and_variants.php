<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Added missing import for DB facade

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cập nhật dữ liệu hiện tại: nếu có stock âm thì set về 0
        DB::statement('UPDATE products SET stock_quantity = 0 WHERE stock_quantity < 0');
        DB::statement('UPDATE product_variants SET stock_quantity = 0 WHERE stock_quantity < 0');

        // Thêm check constraint để đảm bảo stock không âm
        DB::statement('ALTER TABLE products ADD CONSTRAINT check_products_stock_quantity CHECK (stock_quantity >= 0)');
        DB::statement('ALTER TABLE product_variants ADD CONSTRAINT check_variants_stock_quantity CHECK (stock_quantity >= 0)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Không cần rollback vì chỉ thêm validation
    }
};
