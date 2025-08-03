<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('points_earned')->default(0)->after('total_amount');
            $table->integer('points_used')->default(0)->after('points_earned');
            $table->string('point_voucher_code')->nullable()->after('points_used');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['points_earned', 'points_used', 'point_voucher_code']);
        });
    }
};
