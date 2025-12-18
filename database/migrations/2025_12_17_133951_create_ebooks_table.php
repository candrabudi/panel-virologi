<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ebooks', function (Blueprint $table) {
            $table->id();

            $table->uuid('uuid')->unique();
            $table->string('slug')->unique();

            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('summary')->nullable();
            $table->longText('content')->nullable();

            $table->enum('level', [
                'beginner',
                'intermediate',
                'advanced',
            ])->default('beginner');

            $table->enum('topic', [
                'general',
                'network_security',
                'application_security',
                'cloud_security',
                'soc',
                'pentest',
                'malware',
                'incident_response',
                'governance',
            ])->default('general');

            $table->json('chapters')->nullable();
            $table->json('learning_objectives')->nullable();

            $table->json('ai_keywords')->nullable();

            $table->string('cover_image')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_type')->default('pdf');
            $table->unsignedInteger('page_count')->nullable();

            $table->string('author')->nullable();
            $table->date('published_at')->nullable();

            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();

            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
            $table->index(['level', 'topic']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ebooks');
    }
};
