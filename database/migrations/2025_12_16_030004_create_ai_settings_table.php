<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ai_settings', function (Blueprint $table) {
            $table->id();

            $table->string('provider')->default('openai');
            $table->string('base_url')->nullable();
            $table->text('api_key')->nullable();

            $table->string('model')->default('gpt-4.1-mini');
            $table->decimal('temperature', 3, 2)->default(0.7);
            $table->integer('max_tokens')->default(2048);
            $table->integer('timeout')->default(30);

            $table->boolean('is_active')->default(true);
            $table->boolean('cybersecurity_only')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_settings');
    }
};
