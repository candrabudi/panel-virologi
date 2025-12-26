<?php

namespace Database\Seeders;

use App\Models\KnowledgeScope;
use Illuminate\Database\Seeder;

class KnowledgeScopeSeeder extends Seeder
{
    public function run(): void
    {
        KnowledgeScope::updateOrCreate(
            ['code' => 'cybersecurity'],
            ['name' => 'Cyber Security', 'is_active' => true]
        );
    }
}
