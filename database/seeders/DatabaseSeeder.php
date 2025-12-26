<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            KnowledgeScopeSeeder::class,
            KnowledgeScopeTermSeeder::class,
            AssistantIntentSeeder::class,
            AssistantIntentTermSeeder::class,
            AiLanguageAliasSeeder::class,
            AiBehaviorRuleSeeder::class,
            AiResourceRouteSeeder::class,
        ]);
    }
}
