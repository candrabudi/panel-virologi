<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ServerAnomalySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data to avoid cluttering in dev
        DB::table('server_anomalies')->truncate();

        $attacks = [
            // Indonesia
            [
                'event_type' => 'sqli_attempt',
                'ip_address' => '103.120.144.11',
                'country_code' => 'ID',
                'country_name' => 'Indonesia',
                'latitude' => -6.2088,
                'longitude' => 106.8456,
                'details' => "SQLi Pattern: ' OR 1=1 -- detected in search parameter",
                'severity' => 'high',
            ],
            [
                'event_type' => 'rate_limit_exceeded',
                'ip_address' => '114.125.10.55',
                'country_code' => 'ID',
                'country_name' => 'Indonesia',
                'latitude' => -7.2575,
                'longitude' => 112.7521,
                'details' => "Blocked by BotShield: IP hitting /api/login 120 times/min",
                'severity' => 'medium',
            ],
            // USA
            [
                'event_type' => 'bot_blocked',
                'ip_address' => '45.33.22.11',
                'country_code' => 'US',
                'country_name' => 'United States',
                'latitude' => 37.7749,
                'longitude' => -122.4194,
                'details' => "Blocked automated client: Scrapy/2.11.0",
                'severity' => 'low',
            ],
            [
                'event_type' => 'path_traversal',
                'ip_address' => '66.249.66.1',
                'country_code' => 'US',
                'country_name' => 'United States',
                'latitude' => 40.7128,
                'longitude' => -74.0060,
                'details' => "Path traversal attempt: ../../../etc/passwd",
                'severity' => 'critical',
            ],
            // China
            [
                'event_type' => 'probe_scan',
                'ip_address' => '120.55.22.33',
                'country_code' => 'CN',
                'country_name' => 'China',
                'latitude' => 39.9042,
                'longitude' => 116.4074,
                'details' => "Suspicious path scan: /.env, /config.json",
                'severity' => 'medium',
            ],
            // Russia
            [
                'event_type' => 'brute_force',
                'ip_address' => '91.103.65.11',
                'country_code' => 'RU',
                'country_name' => 'Russia',
                'latitude' => 55.7558,
                'longitude' => 37.6173,
                'details' => "Multiple failed login attempts for user 'admin'",
                'severity' => 'high',
            ],
            // Brazil
            [
                'event_type' => 'xss_attempt',
                'ip_address' => '177.100.20.1',
                'country_code' => 'BR',
                'country_name' => 'Brazil',
                'latitude' => -23.5505,
                'longitude' => -46.6333,
                'details' => "XSS Script detected: <script>alert(1)</script>",
                'severity' => 'high',
            ],
            // Germany
            [
                'event_type' => 'bot_blocked',
                'ip_address' => '78.46.20.5',
                'country_code' => 'DE',
                'country_name' => 'Germany',
                'latitude' => 52.5200,
                'longitude' => 13.4050,
                'details' => "Blocked automated client: python-requests/2.31.0",
                'severity' => 'low',
            ],
            // India
            [
                'event_type' => 'api_abuse',
                'ip_address' => '49.207.50.11',
                'country_code' => 'IN',
                'country_name' => 'India',
                'latitude' => 19.0760,
                'longitude' => 72.8777,
                'details' => "Suspicious API usage pattern detected",
                'severity' => 'medium',
            ],
        ];

        foreach ($attacks as $attack) {
            DB::table('server_anomalies')->insert(array_merge($attack, [
                'created_at' => Carbon::now()->subMinutes(rand(1, 1440)),
                'updated_at' => Carbon::now(),
            ]));
        }
    }
}
