<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('receiver_name');
            $table->string('address');
            $table->string('phone');
            $table->decimal('total_amount', 10, 2);
            $table->string('payment_method');
            $table->string('status')->default('pending');
            $table->timestamps();
            $table->softDeletes(); // Thêm dòng này để hỗ trợ xoá mềm
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
