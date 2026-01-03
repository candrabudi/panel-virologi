<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('cyber_security_services', function (Blueprint $table) {
            $table->string('thumbnail')->nullable()->after('short_name');
        });
    }

    public function down(): void
    {
        Schema::table('cyber_security_services', function (Blueprint $table) {
            $table->dropColumn('thumbnail');
        });
    }
};
