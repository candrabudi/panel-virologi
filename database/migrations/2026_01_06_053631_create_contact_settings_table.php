<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contact_settings', function (Blueprint $table) {
            $table->id();

            // Hero Section
            $table->string('hero_badge')->nullable();
            $table->string('hero_title')->nullable();
            $table->text('hero_description')->nullable();

            // Contact Channels (Stored as JSON for flexibility)
            $table->json('channels')->nullable(); // Title, Value, Icon, Description, Link

            // Social Section
            $table->string('social_title')->nullable();
            $table->text('social_description')->nullable();

            // SEO
            $table->string('seo_title')->nullable();
            $table->string('seo_description', 300)->nullable();
            $table->json('seo_keywords')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_settings');
    }
};
