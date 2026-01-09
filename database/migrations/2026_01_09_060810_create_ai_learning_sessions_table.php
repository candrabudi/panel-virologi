<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('ai_learning_sessions')) {
            Schema::create('ai_learning_sessions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('chat_session_id')->constrained('ai_chat_sessions')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users');
                $table->text('user_query');
                $table->text('ai_response');
                $table->text('extracted_insights')->nullable();
                $table->boolean('was_helpful')->nullable();
                $table->integer('feedback_score')->nullable();
                $table->text('correction')->nullable();
                $table->boolean('added_to_knowledge_base')->default(false);
                $table->foreignId('knowledge_base_id')->nullable()->constrained('ai_knowledge_base');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_learning_sessions');
    }
};
