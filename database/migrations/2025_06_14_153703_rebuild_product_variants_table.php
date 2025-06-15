<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Nếu bảng cũ tồn tại, xóa đi
        Schema::dropIfExists('product_variants');

        // Tạo lại bảng đúng định dạng mới
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('variant_name');
            $table->text('image_variant');
            $table->decimal('price', 10, 2);
            $table->integer('stock_quantity');
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('color_id')->nullable();
            $table->unsignedBigInteger('storage_id')->nullable();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('color_id')->references('id')->on('colors')->nullOnDelete();
            $table->foreign('storage_id')->references('id')->on('storages')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
