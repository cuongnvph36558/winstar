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
            $table->string('code');
            $table->string('discount_type');
            $table->string('discount_value');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->decimal('min_order_value', 10, 2);
            $table->decimal('max_discount_value');
            $table->integer('usage_limit');
            $table->integer('usage_limit_per_user');
            $table->tinyInteger('status')->default(1)->comment('0 = không hoạt động, 1 = hoạt động');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
