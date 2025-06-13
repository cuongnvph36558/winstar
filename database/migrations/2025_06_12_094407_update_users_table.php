<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('address', 255);
            $table->string('phone', 18)->unique();
            $table->tinyInteger('status')->default(1);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('address', 255);
            $table->string('phone', 18)->unique();
            $table->tinyInteger('status')->default(1);
        });
    }
};
