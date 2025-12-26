<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ai_language_aliases', function (Blueprint $table) {
            $table->id();

            $table->string('raw_term')->index();
            $table->string('normalized_term')->index();

            $table->string('language_code', 10)->default('unknown')->index();
            $table->string('target_language', 10)->default('id')->index();
            $table->string('scope_code')->nullable();
            $table->unsignedInteger('confidence')->default(1);
            $table->unsignedInteger('used_count')->default(0);

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(
                ['raw_term', 'language_code', 'target_language'],
                'ai_lang_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_language_aliases');
    }
};
