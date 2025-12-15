<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('homepage_heroes', function (Blueprint $table) {
            $table->id();

            $table->string('pre_title')->nullable();
            $table->string('pre_icon')->nullable();

            $table->string('title');
            $table->text('subtitle')->nullable();

            $table->string('video_path')->nullable();

            $table->string('overlay_color')->default('#000000');
            $table->decimal('overlay_opacity', 3, 2)->default(0.5);

            $table->string('primary_button_text')->nullable();
            $table->string('primary_button_url')->nullable();

            $table->string('secondary_button_text')->nullable();
            $table->string('secondary_button_url')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_heroes');
    }
};
