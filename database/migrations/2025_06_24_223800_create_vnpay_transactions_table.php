<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vnpay_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable()->index();
            $table->string('vnp_TxnRef')->nullable();
            $table->bigInteger('vnp_Amount')->nullable();
            $table->string('vnp_ResponseCode', 10)->nullable();
            $table->string('vnp_TransactionNo', 50)->nullable();
            $table->string('vnp_PayDate', 20)->nullable();
            $table->string('vnp_BankCode', 50)->nullable();
            $table->string('vnp_CardType', 50)->nullable();
            $table->string('vnp_SecureHash', 255)->nullable();
            $table->string('status', 20)->nullable(); // success, failed, pending
            $table->string('message')->nullable();
            $table->json('raw_data')->nullable(); // Lưu toàn bộ response nếu cần
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vnpay_transactions');
    }
}; 