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
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date')->comment('Ngày điểm danh');
            $table->time('check_in_time')->nullable()->comment('Thời gian điểm danh vào');
            $table->time('check_out_time')->nullable()->comment('Thời gian điểm danh ra');
            $table->integer('points_earned')->default(0)->comment('Điểm tích được từ điểm danh');
            $table->string('status')->default('present')->comment('Trạng thái: present, absent, late');
            $table->text('notes')->nullable()->comment('Ghi chú');
            $table->timestamps();

            // Đảm bảo mỗi user chỉ có 1 bản ghi điểm danh cho mỗi ngày
            $table->unique(['user_id', 'date']);
            
            // Index để tối ưu truy vấn
            $table->index(['user_id', 'date']);
            $table->index('date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
