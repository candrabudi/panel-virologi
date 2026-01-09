<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('ai_training_data')) {
            Schema::create('ai_training_data', function (Blueprint $table) {
                $table->id();
                $table->string('category', 100);
                $table->text('question');
                $table->text('ideal_answer');
                $table->text('context')->nullable();
                $table->text('metadata')->nullable();
                $table->boolean('is_approved')->default(false);
                $table->foreignId('approved_by')->nullable()->constrained('users');
                $table->timestamp('approved_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_training_data');
    }
};
