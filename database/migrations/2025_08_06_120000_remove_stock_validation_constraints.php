<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Xóa check constraint cho products
        try {
            DB::statement('ALTER TABLE products DROP CONSTRAINT check_products_stock_quantity');
        } catch (\Exception $e) {
            // Constraint có thể không tồn tại, bỏ qua lỗi
        }

        // Xóa check constraint cho product_variants
        try {
            DB::statement('ALTER TABLE product_variants DROP CONSTRAINT check_variants_stock_quantity');
        } catch (\Exception $e) {
            // Constraint có thể không tồn tại, bỏ qua lỗi
        }
    }

    public function down(): void
    {
        // Không cần rollback vì chúng ta muốn xóa constraint vĩnh viễn
    }
}; 