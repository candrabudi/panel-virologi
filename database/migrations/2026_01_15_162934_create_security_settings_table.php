<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('security_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('value');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Insert defaults
        \Illuminate\Support\Facades\DB::table('security_settings')->insert([
            ['key' => 'rate_limiting', 'value' => 'true', 'description' => 'Adaptive AI-driven limits', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'challenge_mode', 'value' => 'true', 'description' => 'JS/Captcha Challenges', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'geo_fencing', 'value' => 'false', 'description' => 'Block high-risk regions', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_settings');
    }
};
