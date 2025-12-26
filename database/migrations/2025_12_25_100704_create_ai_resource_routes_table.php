<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ai_resource_routes', function (Blueprint $table) {
            $table->id();

            $table->string('scope_code', 50)->default('cybersecurity')->index();

            $table->enum('resource_type', ['product', 'service', 'ebook'])->index();
            $table->unsignedBigInteger('resource_id')->index();

            $table->string('keyword', 190)->index();
            $table->unsignedInteger('weight')->default(10)->index();

            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            $table->unique(
                ['scope_code', 'resource_type', 'resource_id', 'keyword'],
                'ai_resource_route_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_resource_routes');
    }
};
