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
        Schema::create('ai_language_stats', function (Blueprint $table) {
            $table->id();
            $table->string('language_code', 10)->index();
            $table->unsignedInteger('total_messages')->default(0);
            $table->unsignedInteger('learning_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_language_stats');
    }
};
