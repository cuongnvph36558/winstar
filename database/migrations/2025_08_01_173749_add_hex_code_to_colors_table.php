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
<<<<<<<< HEAD:database/migrations/2025_08_01_201031_add_vip_level_to_points_table.php
        Schema::table('points', function (Blueprint $table) {
            $table->string('vip_level')->default('Bronze')->after('expired_points');
========
        Schema::table('colors', function (Blueprint $table) {
            $table->string('hex_code', 7)->nullable()->after('name');
>>>>>>>> 4eb64d5f9c184574a7f62e85740fcbbd9c7a0768:database/migrations/2025_08_01_173749_add_hex_code_to_colors_table.php
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
<<<<<<<< HEAD:database/migrations/2025_08_01_201031_add_vip_level_to_points_table.php
        Schema::table('points', function (Blueprint $table) {
            $table->dropColumn('vip_level');
========
        Schema::table('colors', function (Blueprint $table) {
            $table->dropColumn('hex_code');
>>>>>>>> 4eb64d5f9c184574a7f62e85740fcbbd9c7a0768:database/migrations/2025_08_01_173749_add_hex_code_to_colors_table.php
        });
    }
};
