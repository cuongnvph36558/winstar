<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderColumnToCommonTables extends Migration
{
    public function up(): void
    {
        foreach (['categories', 'features', 'services', 'banners'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->integer('order')->nullable()->default(0)->after('id');
            });
        }
    }

    public function down(): void
    {
        foreach (['categories', 'features', 'services', 'banners'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('order');
            });
        }
    }
} 