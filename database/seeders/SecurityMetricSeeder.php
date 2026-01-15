<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SecurityMetricSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('security_metrics')->truncate();

        // Seed last 7 days of metrics
        for($i=6; $i>=0; $i--) {
            DB::table('security_metrics')->insert([
                'active_botnets' => rand(15, 60),
                'c2_nodes_blocked' => rand(120, 450),
                'traffic_scrubbed' => rand(800, 2500) . ' GB',
                'threat_level' => 'HIGH',
                'metric_date' => Carbon::now()->subDays($i)->format('Y-m-d'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
