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
        Schema::table('points', function (Blueprint $table) {
            $table->bigInteger('total_points')->change();
            $table->bigInteger('earned_points')->change();
            $table->bigInteger('used_points')->change();
            $table->bigInteger('expired_points')->change();
        });

        Schema::table('point_transactions', function (Blueprint $table) {
            $table->bigInteger('points')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('points', function (Blueprint $table) {
            $table->integer('total_points')->change();
            $table->integer('earned_points')->change();
            $table->integer('used_points')->change();
            $table->integer('expired_points')->change();
        });

        Schema::table('point_transactions', function (Blueprint $table) {
            $table->integer('points')->change();
        });
    }
};
