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

            $table->string('logo_rectangle')->nullable();
            $table->string('logo_square')->nullable();
            $table->string('favicon')->nullable();

            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            $table->string('copyright_text')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('websites');
    }
};
