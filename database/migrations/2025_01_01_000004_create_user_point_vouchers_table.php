<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_point_vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('point_voucher_id')->constrained()->onDelete('cascade');
            $table->string('voucher_code')->unique(); // Mã voucher
            $table->enum('status', ['active', 'used', 'expired'])->default('active');
            $table->date('expiry_date'); // Ngày hết hạn voucher
            $table->foreignId('used_in_order_id')->nullable()->constrained('orders')->onDelete('set null');
            $table->timestamp('used_at')->nullable(); // Thời gian sử dụng
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('voucher_code');
            $table->index('expiry_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_point_vouchers');
    }
};
