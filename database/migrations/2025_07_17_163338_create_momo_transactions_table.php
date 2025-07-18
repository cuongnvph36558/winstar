<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('momo_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('partner_code')->nullable();
            $table->string('orderId')->nullable();
            $table->string('requestId')->nullable();
            $table->bigInteger('amount')->nullable();
            $table->string('orderInfo')->nullable();
            $table->string('orderType')->nullable();
            $table->string('transId')->nullable();
            $table->integer('resultCode')->nullable();
            $table->string('message')->nullable();
            $table->string('payType')->nullable();
            $table->string('responseTime')->nullable();
            $table->text('extraData')->nullable();
            $table->string('signature')->nullable();
            $table->string('status')->default('pending'); // pending, success, failed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('momo_transactions');
    }
};
