<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('point_vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên voucher
            $table->text('description')->nullable(); // Mô tả
            $table->integer('points_required'); // Số điểm cần để đổi
            $table->decimal('discount_value', 10, 2); // Giá trị giảm giá
            $table->enum('discount_type', ['percentage', 'fixed']); // Loại giảm giá
            $table->decimal('min_order_value', 10, 2)->default(0); // Giá trị đơn hàng tối thiểu
            $table->integer('max_usage')->nullable(); // Số lần sử dụng tối đa
            $table->integer('current_usage')->default(0); // Số lần đã sử dụng
            $table->date('start_date')->nullable(); // Ngày bắt đầu hiệu lực
            $table->date('end_date')->nullable(); // Ngày kết thúc hiệu lực
            $table->boolean('is_active')->default(true); // Trạng thái hoạt động
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_vouchers');
    }
};
