<?php

namespace Database\Seeders;

use App\Models\KnowledgeScope;
use App\Models\KnowledgeScopeTerm;
use Illuminate\Database\Seeder;

class KnowledgeScopeTermSeeder extends Seeder
{
    public function run(): void
    {
        $scope = KnowledgeScope::where('code', 'cybersecurity')->first();

        $terms = [
            ['pentest', 'offensive', 30],
            ['penetration testing', 'offensive', 30],
            ['soc', 'defensive', 30],
            ['siem', 'defensive', 25],
            ['incident response', 'dfir', 25],
            ['forensic', 'dfir', 25],
            ['malware', 'threat', 20],
            ['ransomware', 'threat', 20],
            ['owasp', 'appsec', 20],
            ['xss', 'appsec', 20],
            ['sql injection', 'appsec', 20],
            ['cloud security', 'cloud', 20],
            ['iso 27001', 'grc', 20],
        ];

        foreach ($terms as [$term, $cat, $weight]) {
            KnowledgeScopeTerm::updateOrCreate(
                [
                    'knowledge_scope_id' => $scope->id,
                    'term' => $term,
                ],
                [
                    'category' => $cat,
                    'weight' => $weight,
                    'is_active' => true,
                ]
            );
        }
    }
}
