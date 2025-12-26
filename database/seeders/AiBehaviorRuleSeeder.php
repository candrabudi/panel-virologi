<?php

namespace Database\Seeders;

use App\Models\AiBehaviorRule;
use Illuminate\Database\Seeder;

class AiBehaviorRuleSeeder extends Seeder
{
    public function run(): void
    {
        $rules = [
            ['service', 'keyword', 'pentest', 'direct', 'none', 100],
            ['service', 'keyword', 'soc', 'direct', 'none', 100],
            ['learning', 'keyword', 'belajar', 'guided', 'learning', 80],
            ['general', 'keyword', 'apa aja', 'guided', 'discovery', 70],
            [null, 'keyword', 'produk', 'guided', 'resource_pick', 60],
            [null, 'keyword', 'layanan', 'guided', 'resource_pick', 60],
            [null, 'keyword', 'ebook', 'guided', 'resource_pick', 60],
        ];

        foreach ($rules as [$intent,$type,$value,$decision,$kind,$priority]) {
            AiBehaviorRule::updateOrCreate(
                [
                    'scope_code' => 'cybersecurity',
                    'intent_code' => $intent,
                    'pattern_type' => $type,
                    'pattern_value' => $value,
                ],
                [
                    'decision' => $decision,
                    'guided_kind' => $kind,
                    'priority' => $priority,
                    'is_active' => true,
                ]
            );
        }
    }
}
