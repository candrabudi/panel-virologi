<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ai_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->index();
            $table->enum('role', ['user', 'assistant']);
            $table->longText('content');
            $table->string('response_engine')->nullable();
            $table->boolean('is_liked')->nullable();
            $table->integer('tokens')->nullable();
            $table->integer('latency_ms')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_chat_messages');
    }
};
