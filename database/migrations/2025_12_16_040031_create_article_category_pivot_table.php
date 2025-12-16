<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('article_category_pivot', function (Blueprint $table) {
            $table->id();

            $table->foreignId('article_id')
                ->constrained('articles')
                ->cascadeOnDelete();

            $table->foreignId('article_category_id')
                ->constrained('article_categories')
                ->cascadeOnDelete();

            $table->unique(['article_id', 'article_category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_category_pivot');
    }
};
