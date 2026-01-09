<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('ai_system_prompts')) {
            Schema::create('ai_system_prompts', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100);
                $table->string('version', 20)->default('1.0');
                $table->text('base_prompt');
                $table->text('personality_traits')->nullable();
                $table->text('capabilities')->nullable();
                $table->text('response_templates')->nullable();
                $table->text('custom_rules')->nullable();
                $table->boolean('is_active')->default(true);
                $table->integer('priority')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_system_prompts');
    }
};
