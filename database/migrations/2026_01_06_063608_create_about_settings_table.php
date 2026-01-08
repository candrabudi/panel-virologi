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
        Schema::create('about_settings', function (Blueprint $table) {
            $table->id();

            // Hero Section
            $table->string('hero_badge')->nullable();
            $table->string('hero_title')->nullable();
            $table->text('hero_description')->nullable();
            $table->string('hero_image')->nullable();

            // Core Story / About Us
            $table->string('story_title')->nullable();
            $table->text('story_content')->nullable();
            $table->string('story_image')->nullable();

            // Vision & Mission
            $table->string('vision_title')->nullable();
            $table->text('vision_content')->nullable();
            $table->string('mission_title')->nullable();
            $table->json('mission_items')->nullable();

            // Stats / Achievements
            $table->json('stats')->nullable(); // Title, Value, Suffix (e.g., 'Partners', '100', '+')

            // Dynamic Content Sections
            $table->json('core_values')->nullable(); // Title, Description, Icon
            $table->json('team_members')->nullable(); // Name, Position, Image, Social Links

            // SEO Metadata
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->json('seo_keywords')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_settings');
    }
};
