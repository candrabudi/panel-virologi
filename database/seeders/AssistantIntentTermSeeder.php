<?php

namespace Database\Seeders;

use App\Models\AssistantIntent;
use App\Models\AssistantIntentTerm;
use Illuminate\Database\Seeder;

class AssistantIntentTermSeeder extends Seeder
{
    public function run(): void
    {
        $map = [
            'learning' => [
                'belajar', 'learn', 'learning', 'sinau', 'diajar',
                'roadmap', 'pemula', 'tutorial', 'materi',
            ],
            'product' => [
                'produk', 'product', 'tool', 'software', 'edr', 'xdr', 'siem', 'firewall',
            ],
            'service' => [
                'jasa', 'layanan', 'service', 'pentest', 'audit', 'assessment', 'soc',
            ],
            'ebook' => [
                'ebook', 'e-book', 'buku', 'pdf', 'modul', 'handbook',
            ],
        ];

        foreach ($map as $intentCode => $terms) {
            $intent = AssistantIntent::where('code', $intentCode)->first();
            if (!$intent) {
                continue;
            }

            foreach ($terms as $term) {
                AssistantIntentTerm::updateOrCreate(
                    [
                        'assistant_intent_id' => $intent->id,
                        'term' => $term,
                    ],
                    [
                        'weight' => 10,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
