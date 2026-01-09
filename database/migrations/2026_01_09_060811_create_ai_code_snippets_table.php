<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('ai_code_snippets')) {
            Schema::create('ai_code_snippets', function (Blueprint $table) {
                $table->id();
                $table->string('language', 50);
                $table->string('category', 100);
                $table->string('title', 200);
                $table->text('description');
                $table->text('secure_code');
                $table->text('insecure_code')->nullable();
                $table->text('explanation');
                $table->text('security_benefits')->nullable();
                $table->text('test_cases')->nullable();
                $table->text('dependencies')->nullable();
                $table->integer('usage_count')->default(0);
                $table->decimal('rating', 3, 2)->default(0);
                $table->boolean('is_verified')->default(false);
                $table->timestamps();
                
                $table->fullText(['title', 'description']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_code_snippets');
    }
};
