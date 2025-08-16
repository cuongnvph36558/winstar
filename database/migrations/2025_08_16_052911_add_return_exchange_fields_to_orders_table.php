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
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('return_status', ['none', 'requested', 'approved', 'rejected', 'completed'])->default('none')->after('is_received');
            $table->text('return_reason')->nullable()->after('return_status');
            $table->text('return_description')->nullable()->after('return_reason');
            $table->timestamp('return_requested_at')->nullable()->after('return_description');
            $table->timestamp('return_processed_at')->nullable()->after('return_requested_at');
            $table->string('return_method')->nullable()->after('return_processed_at'); // 'refund', 'exchange', 'credit'
            $table->decimal('return_amount', 15, 2)->nullable()->after('return_method');
            $table->text('admin_return_note')->nullable()->after('return_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'return_status',
                'return_reason', 
                'return_description',
                'return_requested_at',
                'return_processed_at',
                'return_method',
                'return_amount',
                'admin_return_note'
            ]);
        });
    }
};
