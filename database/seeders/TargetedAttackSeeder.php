<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TargetedAttackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('targeted_attacks')->truncate();

        $vectors = ['SQL Injection', 'XSS Stored', 'Brute Force', 'LFI', 'Shell Upload', 'CSRF'];
        $assets = ['Login Page', 'Admin Panel', 'Search API', 'User Profile', 'Payment Gateway'];
        $statuses = ['blocked', 'mitigated', 'investigating', 'active'];
        $severities = ['critical', 'high', 'medium', 'low'];

        $records = [];
        for ($i = 0; $i < 25; $i++) {
            $severity = $severities[array_rand($severities)];
            
            // Critical/High usually Blocked or Investigating
            $status = ($severity === 'critical' || $severity === 'high') 
                ? ($i % 3 == 0 ? 'investigating' : 'blocked') 
                : 'blocked';

            $records[] = [
                'target_url' => 'https://panel-virologi.info' . ($i % 2 == 0 ? '/admin/login' : '/api/v1/user'),
                'attack_vector' => $vectors[array_rand($vectors)],
                'severity' => $severity,
                'affected_asset' => $assets[array_rand($assets)],
                'status' => $status,
                'details' => 'Detected anomalous payload pattern matching known exploit signature #'.rand(1000,9999),
                'incident_at' => Carbon::now()->subMinutes(rand(1, 1440)), // Last 24 hours
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('targeted_attacks')->insert($records);
    }
}
