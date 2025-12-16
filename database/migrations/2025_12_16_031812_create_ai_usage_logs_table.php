<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ai_usage_logs', function (Blueprint $table) {
            $table->id();

            $table->string('provider')->default('openai');
            $table->string('model')->nullable();

            $table->string('category')->default('cybersecurity');

            $table->unsignedInteger('prompt_tokens')->default(0);
            $table->unsignedInteger('completion_tokens')->default(0);
            $table->unsignedInteger('total_tokens')->default(0);

            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();

            $table->boolean('is_blocked')->default(false);
            $table->string('block_reason')->nullable();

            $table->timestamps();

            $table->index(['created_at']);
            $table->index(['ip_address']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_usage_logs');
    }
};
