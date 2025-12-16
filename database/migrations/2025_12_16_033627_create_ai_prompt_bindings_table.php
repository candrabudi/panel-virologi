<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ai_prompt_bindings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ai_context_id')->constrained('ai_contexts')->cascadeOnDelete();
            $table->foreignId('ai_prompt_template_id')->constrained('ai_prompt_templates')->cascadeOnDelete();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['ai_context_id', 'ai_prompt_template_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_prompt_bindings');
    }
};
