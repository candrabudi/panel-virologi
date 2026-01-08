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
        Schema::create('website_settings', function (Blueprint $table) {
            $table->id();
            
            // Identification
            $table->string('site_name')->nullable();
            $table->string('site_tagline')->nullable();
            $table->text('site_description')->nullable();

            // Branding
            $table->string('site_logo')->nullable();
            $table->string('site_logo_footer')->nullable();
            $table->string('site_favicon')->nullable();
            
            // Contact
            $table->string('site_email')->nullable();
            $table->string('site_phone')->nullable();
            $table->string('site_copyright')->nullable();
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            
            // Verification / Analytics
            $table->string('google_analytics_id')->nullable();
            $table->string('google_console_verification')->nullable();
            
            // Custom Scripts
            $table->text('custom_head_scripts')->nullable();
            $table->text('custom_body_scripts')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_settings');
    }
};
