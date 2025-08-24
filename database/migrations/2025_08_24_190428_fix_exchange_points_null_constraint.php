<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            // First, update any existing null values to 0
            DB::statement('UPDATE coupons SET exchange_points = 0 WHERE exchange_points IS NULL');
            
            // Then modify the column to ensure it's NOT NULL with default 0
            $table->integer('exchange_points')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->integer('exchange_points')->nullable()->change();
        });
    }
};
