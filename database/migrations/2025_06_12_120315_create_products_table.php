<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('image');
        $table->text('description')->nullable();
        $table->unsignedBigInteger('category_id');
        $table->tinyInteger('status')->default(1)->comment('0 = không hoạt động, 1 = hoạt động');
        $table->integer('view')->nullable();
        $table->timestamps();
        $table->softDeletes();

        // Khóa ngoại đến bảng categories
        $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
