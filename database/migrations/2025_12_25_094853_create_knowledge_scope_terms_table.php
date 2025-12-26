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
        Schema::create('knowledge_scope_terms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('knowledge_scope_id')->index();
            $table->string('term')->index();
            $table->string('category')->nullable();
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
        Schema::dropIfExists('knowledge_scope_terms');
    }
};
