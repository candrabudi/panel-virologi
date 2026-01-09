<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('ai_knowledge_base')) {
            Schema::create('ai_knowledge_base', function (Blueprint $table) {
                $table->id();
                $table->string('category', 50);
                $table->string('topic', 200);
                $table->text('content');
                $table->text('context')->nullable();
                $table->text('examples')->nullable();
                $table->text('references')->nullable();
                $table->text('tags')->nullable();
                $table->text('embedding')->nullable();
                $table->integer('usage_count')->default(0);
                $table->decimal('relevance_score', 5, 2)->default(0);
                $table->string('source', 50)->default('user_interaction');
                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->timestamp('last_used_at')->nullable();
                $table->timestamps();
                
                $table->fullText(['topic', 'content']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_knowledge_base');
    }
};
