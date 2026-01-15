<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AiUsageLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Clear existing logs for a clean chart demo
        DB::table('ai_usage_logs')->truncate();

        $records = [];
        $providers = ['openai', 'anthropic', 'google'];
        $models = ['gpt-4', 'gpt-3.5-turbo', 'claude-3-opus', 'gemini-pro'];
        $categories = ['cybersecurity', 'general', 'code', 'analysis'];
        
        // Generate data for the last 30 days
        $startDate = Carbon::now()->subDays(29);
        $endDate = Carbon::now();

        // Simulate traffic trend (increasing slightly)
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            
            // Random daily volume: 50 - 200 requests
            $dailyVolume = rand(50, 200); 
            
            // Introduce some variance/spikes
            if (rand(1, 10) > 8) $dailyVolume += rand(100, 300);

            for ($i = 0; $i < $dailyVolume; $i++) {
                $isBlocked = rand(1, 100) <= 5; // 5% block rate
                $tokens = rand(100, 4000);
                
                $records[] = [
                    'provider' => $providers[array_rand($providers)],
                    'model' => $models[array_rand($models)],
                    'category' => $categories[array_rand($categories)],
                    'prompt_tokens' => floor($tokens * 0.3),
                    'completion_tokens' => floor($tokens * 0.7),
                    'total_tokens' => $tokens,
                    'ip_address' => '192.168.1.' . rand(1, 255),
                    'user_agent' => 'Mozilla/5.0 (Dummy Seeder)',
                    'is_blocked' => $isBlocked,
                    'block_reason' => $isBlocked ? 'Rate Limit Exceeded' : null,
                    'created_at' => $date->copy()->addSeconds(rand(1, 86000)),
                    'updated_at' => $date->copy()->addSeconds(rand(1, 86000)),
                ];
            }

            // Insert in chunks to avoid memory issues
            if (count($records) > 500) {
                DB::table('ai_usage_logs')->insert($records);
                $records = [];
            }
        }

        // Insert remaining
        if (count($records) > 0) {
            DB::table('ai_usage_logs')->insert($records);
        }
    }
}
