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
        Schema::create('leak_check_settings', function (Blueprint $table) {
            $table->id();
            $table->string('api_token')->nullable();
            $table->integer('default_limit')->default(100);
            $table->string('lang')->default('en');
            $table->string('bot_name')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leak_check_settings');
    }
};
