<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'compare_price')) {
                $table->decimal('compare_price', 15, 2)->nullable()->after('promotion_price')->comment('Giá so sánh (giá gốc)');
            }
        });
    }

    /**
     * reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'compare_price')) {
                $table->dropColumn('compare_price');
            }
        });
    }
}; 