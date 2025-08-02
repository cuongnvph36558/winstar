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
        Schema::table('coupons', function (Blueprint $table) {
            $table->string('name')->nullable()->after('code');
            $table->text('description')->nullable()->after('name');
            $table->string('vip_level')->nullable()->after('exchange_points');
            $table->integer('validity_days')->default(30)->after('vip_level');
            $table->decimal('max_discount', 10, 2)->nullable()->after('max_discount_value');
            $table->integer('used_count')->default(0)->after('usage_limit_per_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn([
                'name',
                'description', 
                'vip_level',
                'validity_days',
                'max_discount',
                'used_count'
            ]);
        });
    }
};
