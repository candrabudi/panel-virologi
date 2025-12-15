<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique();

            $table->text('description')->nullable();
            $table->longText('content')->nullable();

            $table->string('thumbnail')->nullable();

            $table->string('seo_title')->nullable();
            $table->string('seo_description', 300)->nullable();
            $table->text('seo_keywords')->nullable();

            $table->string('og_title')->nullable();
            $table->string('og_description', 300)->nullable();
            $table->string('og_image')->nullable();

            $table->string('canonical_url')->nullable();

            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
