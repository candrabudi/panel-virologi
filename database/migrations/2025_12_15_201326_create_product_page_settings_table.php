<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_page_settings', function (Blueprint $table) {
            $table->id();

            $table->string('page_title')->nullable();
            $table->string('page_subtitle')->nullable();
            $table->string('background_video')->nullable();

            $table->string('cta_text')->nullable();
            $table->string('cta_url')->nullable();

            $table->string('seo_title')->nullable();
            $table->string('seo_description', 300)->nullable();
            $table->text('seo_keywords')->nullable();

            $table->string('og_title')->nullable();
            $table->string('og_description', 300)->nullable();
            $table->string('og_image')->nullable();

            $table->string('canonical_url')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_page_settings');
    }
};
