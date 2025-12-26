<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ai_system_prompts', function (Blueprint $table) {
            $table->id();

            $table->string('scope_code', 50)->index();
            $table->string('code', 100)->index();

            $table->string('intent_code', 50)->nullable()->index();
            $table->string('behavior', 50)->nullable()->index();
            $table->string('resource_type', 50)->nullable()->index();

            $table->text('content');

            $table->unsignedInteger('priority')->default(50)->index();
            $table->boolean('is_active')->default(true)->index();

            $table->timestamps();

            $table->unique(['scope_code', 'code'], 'ai_system_prompts_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_system_prompts');
    }
};
