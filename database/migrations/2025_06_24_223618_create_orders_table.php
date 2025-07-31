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
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('code_order')->nullable();
            $table->string('receiver_name');
            $table->string('billing_city')->nullable();
            $table->string('billing_district')->nullable(); 
            $table->string('billing_ward')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('phone');
            $table->string('description')->nullable();
            $table->decimal('total_amount', 15, 2);
            $table->string('payment_method');
            $table->enum('status', ['pending', 'processing', 'shipping', 'completed', 'cancelled'])->default('pending');
            $table->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('payment_status', ['pending', 'paid','processing', 'completed', 'failed','refunded','cancelled'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
