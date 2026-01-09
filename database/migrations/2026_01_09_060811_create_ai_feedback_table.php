<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('ai_feedback')) {
            Schema::create('ai_feedback', function (Blueprint $table) {
                $table->id();
                $table->foreignId('chat_message_id')->constrained('ai_chat_messages')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users');
                $table->enum('feedback_type', ['helpful', 'not_helpful', 'incorrect', 'incomplete', 'excellent']);
                $table->integer('rating')->nullable();
                $table->text('comment')->nullable();
                $table->text('suggested_improvement')->nullable();
                $table->boolean('reviewed_by_admin')->default(false);
                $table->boolean('applied_to_training')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_feedback');
    }
};
