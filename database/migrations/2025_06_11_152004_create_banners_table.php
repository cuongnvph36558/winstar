<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('image_url');
            $table->string('link')->nullable();
            $table->string('title');
            $table->date('start_date');
            $table->date('end_date');
            $table->tinyInteger('status')->default(1)->comment('0 = không hoạt động, 1 = hoạt động');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
