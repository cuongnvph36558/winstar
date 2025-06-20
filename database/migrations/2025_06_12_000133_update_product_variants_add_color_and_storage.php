<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->unsignedBigInteger('color_id')->nullable()->after('deleted_at');
            $table->unsignedBigInteger('storage_id')->nullable()->after('color_id');

            $table->foreign('color_id')->references('id')->on('colors')->onDelete('set null');
            $table->foreign('storage_id')->references('id')->on('storages')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropForeign(['color_id']);
            $table->dropForeign(['storage_id']);
            $table->dropColumn(['color_id', 'storage_id']);
        });
    }
};
