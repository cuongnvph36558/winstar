<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // 'earn', 'use', 'expire', 'bonus'
            $table->integer('points'); // Số điểm (+ hoặc -)
            $table->string('description'); // Mô tả giao dịch
            $table->string('reference_type')->nullable(); // 'order', 'voucher', 'bonus'
            $table->unsignedBigInteger('reference_id')->nullable(); // ID của đối tượng liên quan
            $table->date('expiry_date')->nullable(); // Ngày hết hạn điểm
            $table->boolean('is_expired')->default(false); // Đã hết hạn chưa
            $table->timestamps();

            $table->index(['user_id', 'type']);
            $table->index(['reference_type', 'reference_id']);
            $table->index('expiry_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_transactions');
    }
};
