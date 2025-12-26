<?php

namespace Database\Seeders;

use App\Models\AssistantIntent;
use Illuminate\Database\Seeder;

class AssistantIntentSeeder extends Seeder
{
    public function run(): void
    {
        $intents = [
            'general' => 'General',
            'learning' => 'Learning',
            'product' => 'Product',
            'service' => 'Service',
            'ebook' => 'Ebook',
        ];

        foreach ($intents as $code => $name) {
            AssistantIntent::updateOrCreate(
                ['code' => $code],
                ['name' => $name, 'is_active' => true]
            );
        }
    }
}
