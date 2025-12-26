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
        Schema::create('assistant_intent_terms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assistant_intent_id')->index();
            $table->string('term')->index();
            $table->unsignedInteger('weight')->default(10);
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assistant_intent_terms');
    }
};
