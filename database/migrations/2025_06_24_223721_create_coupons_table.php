<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('discount_type', ['percentage', 'fixed']);
            $table->decimal('min_order_value', 10, 2)->nullable();
            $table->decimal('discount_value', 10, 2);
            $table->decimal('max_discount_value', 10, 2)->nullable(); // ✅ Đã thêm
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('usage_limit')->nullable();
            $table->integer('usage_limit_per_user')->nullable();     // ✅ Đã thêm
            $table->tinyInteger('status')->default(1);               // ✅ Đã thêm
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
