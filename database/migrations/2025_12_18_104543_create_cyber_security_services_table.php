<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cyber_security_services', function (Blueprint $table) {
            $table->id();

            $table->string('slug')->unique();
            $table->string('name');
            $table->string('short_name')->nullable();

            $table->enum('category', [
                'soc',
                'pentest',
                'audit',
                'incident_response',
                'cloud_security',
                'governance',
                'training',
                'consulting',
            ])->index();

            $table->text('summary')->nullable();
            $table->longText('description')->nullable();

            $table->json('service_scope')->nullable();
            $table->json('deliverables')->nullable();
            $table->json('target_audience')->nullable();

            $table->json('ai_keywords')->nullable();
            $table->string('ai_domain')->default('cybersecurity');
            $table->boolean('is_ai_visible')->default(true);

            $table->string('cta_label')->default('Hubungi Kami');
            $table->string('cta_url')->nullable();

            $table->string('seo_title')->nullable();
            $table->string('seo_description', 300)->nullable();
            $table->json('seo_keywords')->nullable();

            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index(['is_active', 'is_ai_visible']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cyber_security_services');
    }
};
