<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('total_points')->default(0); // Tổng điểm hiện tại
            $table->integer('earned_points')->default(0); // Tổng điểm đã tích
            $table->integer('used_points')->default(0); // Tổng điểm đã sử dụng
            $table->integer('expired_points')->default(0); // Tổng điểm đã hết hạn
            $table->timestamps();

            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('points');
    }
};
