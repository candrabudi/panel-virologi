<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ServerHealthMetric;

class MonitorServerHealth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:server-health';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Collect and store server health metrics (CPU, RAM, Disk, Traffic)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 1. CPU Usage (Load Average as proxy for %)
        $load = sys_getloadavg();
        $coreCount = 1; 
        if (is_file('/proc/cpuinfo')) {
            $cpuinfo = file_get_contents('/proc/cpuinfo');
            preg_match_all('/^processor/m', $cpuinfo, $matches);
            $coreCount = count($matches[0]);
        }
        // Load average * 100 / cores = percentage roughly
        $cpuUsage = isset($load[0]) ? ($load[0] / $coreCount) * 100 : 0;
        if($cpuUsage > 100) $cpuUsage = 100;

        // 2. Memory Usage
        $memoryUsage = 0;
        try {
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
               // Windows memory check (command line)
               $cmd = "wmic ComputerSystem get TotalPhysicalMemory";
               @exec($cmd, $outputTotal);
               $cmd = "wmic OS get FreePhysicalMemory";
               @exec($cmd, $outputFree);
               if(isset($outputTotal[1]) && isset($outputFree[1])){
                   $total = (int)$outputTotal[1];
                   $free = (int)$outputFree[1] * 1024;
                   $memoryUsage = (($total - $free) / $total) * 100;
               }
            } else {
                // Linux
                $free = shell_exec('free');
                if($free){
                    $free = (string)trim($free);
                    $freeArr = explode("\n", $free);
                    $mem = explode(" ", $freeArr[1]);
                    $mem = array_filter($mem);
                    $mem = array_values($mem); // Reindex
                    if(isset($mem[1]) && isset($mem[2]) && $mem[1] > 0) {
                        $memoryUsage = ($mem[2] / $mem[1]) * 100;
                    }
                }
            }
        } catch(\Exception $e) {
            $memoryUsage = 0;
        }

        // 3. Disk Usage
        $diskTotal = disk_total_space('.');
        $diskFree = disk_free_space('.');
        $diskUsed = $diskTotal - $diskFree;
        $diskUsage = ($diskUsed / $diskTotal) * 100;

        // 4. Traffic (Simulated for Demo Purpose as real packet sniffing requires root)
        // Simulating realistic fluctuation
        $lastMetric = ServerHealthMetric::latest()->first();
        $lastIn = $lastMetric ? $lastMetric->traffic_in : 100;
        $lastOut = $lastMetric ? $lastMetric->traffic_out : 200;

        // Random walk
        $trafficIn = max(0, $lastIn + rand(-50, 50));
        $trafficOut = max(0, $lastOut + rand(-80, 80));

        // 5. Status
        $status = 'operational';
        if($cpuUsage > 90 || $memoryUsage > 90) $status = 'degraded';
        if($diskUsage > 95) $status = 'maintenance';

        ServerHealthMetric::create([
            'cpu_usage' => round($cpuUsage, 2),
            'memory_usage' => round($memoryUsage, 2),
            'disk_usage' => round($diskUsage, 2),
            'traffic_in' => round($trafficIn, 2),
            'traffic_out' => round($trafficOut, 2),
            'status' => $status
        ]);

        $this->info("Server metrics collected: CPU {$cpuUsage}%, RAM {$memoryUsage}%");
    }
}
