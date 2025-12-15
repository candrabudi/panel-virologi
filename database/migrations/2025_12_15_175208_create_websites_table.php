<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('websites', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('tagline')->nullable();

            $table->text('description')->nullable();
            $table->longText('long_description')->nullable();

            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();

            $table->string('logo_rectangle')->nullable();
            $table->string('logo_square')->nullable();
            $table->string('favicon')->nullable();

            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();

            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('og_type')->default('website');
            $table->string('og_url')->nullable();

            $table->string('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_image')->nullable();
            $table->string('twitter_card')->default('summary_large_image');

            $table->string('canonical_url')->nullable();
            $table->string('robots_meta')->default('index, follow');

            $table->json('extra_meta')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('websites');
    }
};
