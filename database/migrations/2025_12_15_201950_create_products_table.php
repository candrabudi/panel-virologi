<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();

            $table->string('name');
            $table->string('subtitle')->nullable();

            $table->text('summary')->nullable();
            $table->longText('content')->nullable();

            $table->enum('product_type', [
                'digital',
                'hardware',
                'service',
                'bundle',
            ])->index();

            $table->enum('ai_domain', [
                'general',
                'network_security',
                'application_security',
                'cloud_security',
                'soc',
                'pentest',
                'malware',
                'incident_response',
                'governance',
            ])->default('general')->index();

            $table->enum('ai_level', [
                'beginner',
                'intermediate',
                'advanced',
                'all',
            ])->default('all')->index();

            $table->json('ai_keywords')->nullable();
            $table->json('ai_intents')->nullable();
            $table->json('ai_use_cases')->nullable();

            $table->unsignedSmallInteger('ai_priority')->default(0)->index();
            $table->boolean('is_ai_visible')->default(true);
            $table->boolean('is_ai_recommended')->default(true);

            $table->string('cta_label')->nullable();
            $table->string('cta_url')->nullable();
            $table->enum('cta_type', [
                'internal',
                'external',
                'whatsapp',
                'form',
            ])->default('external');

            $table->string('thumbnail')->nullable();

            $table->string('seo_title')->nullable();
            $table->string('seo_description', 300)->nullable();
            $table->json('seo_keywords')->nullable();
            $table->string('canonical_url')->nullable();

            $table->unsignedInteger('ai_view_count')->default(0);
            $table->unsignedInteger('ai_click_count')->default(0);

            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0)->index();

            $table->timestamps();

            $table->index(['product_type', 'ai_domain', 'ai_level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
