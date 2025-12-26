<?php

namespace Database\Seeders;

use App\Models\AiLanguageAlias;
use Illuminate\Database\Seeder;

class AiLanguageAliasSeeder extends Seeder
{
    public function run(): void
    {
        $pairs = [
            ['id', 'bljr', 'belajar'],
            ['id', 'pgn', 'ingin'],
            ['id', 'pngn', 'ingin'],
            ['id', 'gmn', 'bagaimana'],
            ['id', 'gmna', 'bagaimana'],
            ['id', 'cybr', 'cyber'],
            ['id', 'scrity', 'security'],
            ['id', 'sec', 'security'],

            ['en', 'learn', 'belajar'],
            ['en', 'learning', 'belajar'],
            ['en', 'how', 'bagaimana'],
            ['en', 'security', 'security'],
            ['en', 'cyber', 'cyber'],

            ['jv', 'sinau', 'belajar'],
            ['jv', 'piye', 'bagaimana'],
            ['jv', 'arep', 'ingin'],

            ['su', 'diajar', 'belajar'],
            ['su', 'kumaha', 'bagaimana'],
            ['su', 'hoyong', 'ingin'],
        ];

        foreach ($pairs as [$lang, $raw, $norm]) {
            AiLanguageAlias::updateOrCreate(
                [
                    'scope_code' => 'cybersecurity',
                    'language_code' => $lang,
                    'target_language' => 'id',
                    'raw_term' => $raw,
                ],
                [
                    'normalized_term' => $norm,
                    'confidence' => 10,
                    'used_count' => 0,
                    'is_active' => true,
                ]
            );
        }
    }
}
