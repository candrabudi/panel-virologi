<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ServerHealthMetricSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('server_health_metrics')->truncate();

        // Seed last 60 minutes of data (one record per minute)
        for($i=60; $i>=0; $i--) {
            DB::table('server_health_metrics')->insert([
                'cpu_usage' => rand(10, 80) + (rand(0, 99) / 100),
                'memory_usage' => rand(30, 70) + (rand(0, 99) / 100),
                'disk_usage' => 45.5, // Usually stable
                'traffic_in' => rand(50, 500) + (rand(0, 99) / 100),
                'traffic_out' => rand(100, 800) + (rand(0, 99) / 100),
                'status' => 'operational',
                'created_at' => Carbon::now()->subMinutes($i),
                'updated_at' => Carbon::now()->subMinutes($i),
            ]);
        }
    }
}
