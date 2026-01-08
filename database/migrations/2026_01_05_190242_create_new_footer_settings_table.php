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
        Schema::dropIfExists('footer_settings');

        Schema::create('footer_settings', function (Blueprint $table) {
            $table->id();
            
            // Company Info
            $table->text('description')->nullable();
            $table->text('address')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            
            // Copyright
            $table->string('copyright_text')->nullable()->default('© 2026 RD-VIROLOGI. All rights reserved.');
            
            // Social Media (JSON: { "twitter": "#", "linkedin": "#" })
            $table->json('social_links')->nullable();
            
            // Footer Columns (JSON: [{"text": "Home", "url": "/"}, ...])
            $table->string('column_1_title')->nullable();
            $table->json('column_1_links')->nullable();
            
            $table->string('column_2_title')->nullable();
            $table->json('column_2_links')->nullable();
            
            $table->string('column_3_title')->nullable();
            $table->json('column_3_links')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('footer_settings');
    }
};
