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
            // thông tin sản phẩm
            $table->string('brand')->nullable()->after('description')->comment('Thương hiệu sản phẩm');
            $table->string('model')->nullable()->after('brand')->comment('Model sản phẩm');
            $table->string('sku', 100)->nullable()->after('model')->comment('Mã SKU');
            
            // thông tin vật lý
            $table->decimal('weight', 8, 2)->nullable()->after('sku')->comment('Trọng lượng (kg)');
            $table->decimal('length', 8, 1)->nullable()->after('weight')->comment('Chiều dài (cm)');
            $table->decimal('width', 8, 1)->nullable()->after('length')->comment('Chiều rộng (cm)');
            $table->decimal('height', 8, 1)->nullable()->after('width')->comment('Chiều cao (cm)');
            
            // thông tin bảo hành và xuất xứ
            $table->integer('warranty')->nullable()->after('height')->comment('Thời gian bảo hành (tháng)');
            $table->string('origin')->nullable()->after('warranty')->comment('Xuất xứ sản phẩm');
            
            // thông tin SEO
            $table->text('meta_keywords')->nullable()->after('origin')->comment('Từ khóa SEO');
            $table->text('meta_description')->nullable()->after('meta_keywords')->comment('Mô tả SEO');
        });
    }

    /**
     * reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'brand',
                'model', 
                'sku',
                'weight',
                'length',
                'width',
                'height',
                'warranty',
                'origin',
                'meta_keywords',
                'meta_description'
            ]);
        });
    }
};
