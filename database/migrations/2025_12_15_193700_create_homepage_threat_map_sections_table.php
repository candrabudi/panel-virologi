<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('homepage_threat_map_sections', function (Blueprint $table) {
            $table->id();

            $table->string('pre_title')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();

            $table->string('cta_text')->nullable();
            $table->string('cta_url')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_threat_map_sections');
    }
};
