<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('about_us', function (Blueprint $table) {
            $table->id();

            $table->string('breadcrumb_pre')->nullable();
            $table->string('breadcrumb_bg')->nullable();
            $table->string('page_title')->nullable();

            $table->string('headline')->nullable();

            $table->longText('left_content')->nullable();
            $table->longText('right_content')->nullable();

            $table->json('topics')->nullable();
            $table->json('manifesto')->nullable();

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
        Schema::dropIfExists('about_us');
    }
};
