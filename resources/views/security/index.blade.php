@extends('layouts.app')

@section('title', 'Cyber Command Center')

@section('content')
    <div class="col-span-12">
        <div class="intro-y flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">
                Cyber Command Center
            </h2>
            <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">
                <div class="flex items-center text-slate-500 text-xs mr-4">
                    <div class="w-2 h-2 bg-success rounded-full mr-2 animate-pulse"></div>
                    System Operational
                </div>
                <button class="btn btn-primary" onclick="window.location.reload()">
                    <i data-lucide="refresh-cw" class="w-4 h-4 mr-2"></i> Refresh Intel
                </button>
            </div>
        </div>
    </div>

    {{-- ROW 1: BOTSHIELD METRICS --}}
    <div class="col-span-12 grid grid-cols-12 gap-6 mt-5">
        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
            <div class="box p-5 border-b-4 border-danger relative overflow-hidden">
                <div class="flex items-center">
                   <div class="w-full">
                        <div class="text-slate-500 text-xs font-bold uppercase tracking-wider">Active Botnets</div>
                        <div class="text-2xl font-medium mt-1" id="stat-botnets">--</div>
                        <div class="text-danger text-xs mt-1 flex items-center">
                            <i data-lucide="alert-octagon" class="w-3 h-3 mr-1"></i> Detected & Isolated
                        </div>
                   </div>
                   <div class="absolute right-0 top-0 p-5 opacity-10">
                       <i data-lucide="cpu" class="w-16 h-16 text-danger"></i>
                   </div>
                </div>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
            <div class="box p-5 border-b-4 border-warning relative overflow-hidden">
                <div class="flex items-center">
                   <div class="w-full">
                        <div class="text-slate-500 text-xs font-bold uppercase tracking-wider">C2 Nodes Blocked</div>
                        <div class="text-2xl font-medium mt-1" id="stat-c2">--</div>
                         <div class="text-warning text-xs mt-1 flex items-center">
                            <i data-lucide="shield" class="w-3 h-3 mr-1"></i> Command & Control
                        </div>
                   </div>
                     <div class="absolute right-0 top-0 p-5 opacity-10">
                       <i data-lucide="radio" class="w-16 h-16 text-warning"></i>
                   </div>
                </div>
            </div>
        </div>
         <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
            <div class="box p-5 border-b-4 border-primary relative overflow-hidden">
                <div class="flex items-center">
                   <div class="w-full">
                        <div class="text-slate-500 text-xs font-bold uppercase tracking-wider">Traffic Scrubbed</div>
                        <div class="text-2xl font-medium mt-1" id="stat-traffic">--</div>
                         <div class="text-primary text-xs mt-1 flex items-center">
                            <i data-lucide="activity" class="w-3 h-3 mr-1"></i> Malicious Packets
                        </div>
                   </div>
                    <div class="absolute right-0 top-0 p-5 opacity-10">
                       <i data-lucide="filter" class="w-16 h-16 text-primary"></i>
                   </div>
                </div>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
            <div class="box p-5 border-b-4 border-slate-500 relative overflow-hidden">
                <div class="flex items-center">
                   <div class="w-full">
                        <div class="text-slate-500 text-xs font-bold uppercase tracking-wider">Threat Level</div>
                        <div class="text-2xl font-medium mt-1 text-danger" id="stat-threat">HIGH</div>
                         <div class="text-slate-500 text-xs mt-1 flex items-center">
                            <i data-lucide="thermometer" class="w-3 h-3 mr-1"></i> Defcon 3
                        </div>
                   </div>
                   <div class="absolute right-0 top-0 p-5 opacity-10">
                       <i data-lucide="siren" class="w-16 h-16 text-slate-800 dark:text-white"></i>
                   </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ROW 2: INFRASTRUCTURE MONITOR --}}
    <div class="col-span-12 mt-5">
        <div class="intro-y flex items-center h-10">
             <h2 class="text-lg font-medium truncate mr-5">
                Infrastructure Monitor
            </h2>
        </div>
        <div class="grid grid-cols-12 gap-6 mt-2">
            {{-- RESOURCES (CIRCULAR) --}}
            <div class="col-span-12 md:col-span-4 box p-5 intro-y">
                <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                    <div class="font-medium text-base truncate">System Resources</div>
                    <div class="ml-auto text-xs font-medium text-success" id="server-status-text">OPERATIONAL</div>
                </div>
                
                <div class="grid grid-cols-3 gap-2">
                    {{-- CPU --}}
                    <div class="flex flex-col items-center">
                        <div class="relative w-24 h-24">
                            <canvas id="cpu-chart"></canvas>
                            <div class="absolute inset-0 flex items-center justify-center font-bold text-slate-700 dark:text-slate-200" id="cpu-val-center">0%</div>
                        </div>
                        <div class="text-xs text-slate-500 mt-3 font-medium">CPU Load</div>
                    </div>

                    {{-- RAM --}}
                    <div class="flex flex-col items-center">
                        <div class="relative w-24 h-24">
                            <canvas id="ram-chart"></canvas>
                             <div class="absolute inset-0 flex items-center justify-center font-bold text-slate-700 dark:text-slate-200" id="ram-val-center">0%</div>
                        </div>
                         <div class="text-xs text-slate-500 mt-3 font-medium">Memory</div>
                    </div>

                    {{-- DISK --}}
                     <div class="flex flex-col items-center">
                        <div class="relative w-24 h-24">
                            <canvas id="disk-chart"></canvas>
                             <div class="absolute inset-0 flex items-center justify-center font-bold text-slate-700 dark:text-slate-200" id="disk-val-center">0%</div>
                        </div>
                         <div class="text-xs text-slate-500 mt-3 font-medium">Storage</div>
                    </div>
                </div>
            </div>

            {{-- TRAFFIC CHART --}}
            <div class="col-span-12 md:col-span-8 box p-5 intro-y">
                 <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                    <div class="font-medium text-base truncate">Network Traffic (Realtime)</div>
                     <div class="ml-auto flex items-center gap-4">
                         <div class="flex items-center">
                            <div class="w-2 h-2 bg-primary rounded-full mr-2"></div> 
                            <span class="text-xs text-slate-500 mr-1">Inbound:</span>
                            <span class="text-xs font-medium" id="traffic-in-val">0 Mbps</span>
                         </div>
                         <div class="flex items-center">
                            <div class="w-2 h-2 bg-warning rounded-full mr-2"></div> 
                            <span class="text-xs text-slate-500 mr-1">Outbound:</span>
                             <span class="text-xs font-medium" id="traffic-out-val">0 Mbps</span>
                         </div>
                     </div>
                </div>
                <div class="h-[200px]">
                    <canvas id="traffic-chart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-12 grid grid-cols-12 gap-6 mt-5">
        
        {{-- LEFT: THREAT INTEL FEED (8 COLS) --}}
        <div class="col-span-12 xl:col-span-8 intro-y">
            <div class="box">
                <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <h2 class="font-medium text-base mr-auto">
                        Live Cyber Threat Intelligence
                    </h2>
                    <div class="bg-danger/20 text-danger text-xs font-medium px-2 py-1 rounded border border-danger/20 flex items-center animate-pulse">
                        <i data-lucide="wifi" class="w-3 h-3 mr-1"></i> LIVE FEED
                    </div>
                </div>
                <div class="p-5">
                    <div class="overflow-x-auto">
                        <table class="table table-hover w-full">
                            <thead>
                                <tr class="bg-slate-50/50 dark:bg-darkmode-600/50">
                                    <th class="font-medium px-5 py-4 border-b-2 border-slate-200/60 dark:border-darkmode-300 whitespace-nowrap text-left text-xs uppercase text-slate-500">Timestamp</th>
                                    <th class="font-medium px-5 py-4 border-b-2 border-slate-200/60 dark:border-darkmode-300 whitespace-nowrap text-left text-xs uppercase text-slate-500">Attack Vector</th>
                                    <th class="font-medium px-5 py-4 border-b-2 border-slate-200/60 dark:border-darkmode-300 whitespace-nowrap text-left text-xs uppercase text-slate-500">Source</th>
                                    <th class="font-medium px-5 py-4 border-b-2 border-slate-200/60 dark:border-darkmode-300 whitespace-nowrap text-left text-xs uppercase text-slate-500">Target</th>
                                    <th class="font-medium px-5 py-4 border-b-2 border-slate-200/60 dark:border-darkmode-300 whitespace-nowrap text-center text-xs uppercase text-slate-500">Status</th>
                                </tr>
                            </thead>
                            <tbody id="attack-feed-body">
                                <tr>
                                    <td colspan="5" class="text-center text-slate-500 py-10">Initializing Neural Feed...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- TARGETED THREATS (SITE SPECIFIC) --}}
            <div class="box mt-5">
                <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <h2 class="font-medium text-base mr-auto">
                        Targeted Threat Feed (Local Scope)
                    </h2>
                     <div class="bg-primary/20 text-primary text-xs font-medium px-2 py-1 rounded border border-primary/20 flex items-center">
                        <i data-lucide="crosshair" class="w-3 h-3 mr-1"></i> SITE SPECIFIC
                    </div>
                </div>
                <div class="p-5">
                    <div class="overflow-x-auto">
                        <table class="table table-hover w-full">
                            <thead>
                                <tr class="bg-slate-50/50 dark:bg-darkmode-600/50">
                                    <th class="font-medium px-5 py-4 border-b-2 border-slate-200/60 dark:border-darkmode-300 whitespace-nowrap text-left text-xs uppercase text-slate-500">Timestamp</th>
                                    <th class="font-medium px-5 py-4 border-b-2 border-slate-200/60 dark:border-darkmode-300 whitespace-nowrap text-left text-xs uppercase text-slate-500">Target URL</th>
                                    <th class="font-medium px-5 py-4 border-b-2 border-slate-200/60 dark:border-darkmode-300 whitespace-nowrap text-left text-xs uppercase text-slate-500">Vector</th>
                                    <th class="font-medium px-5 py-4 border-b-2 border-slate-200/60 dark:border-darkmode-300 whitespace-nowrap text-left text-xs uppercase text-slate-500">Severity</th>
                                    <th class="font-medium px-5 py-4 border-b-2 border-slate-200/60 dark:border-darkmode-300 whitespace-nowrap text-center text-xs uppercase text-slate-500">Status</th>
                                </tr>
                            </thead>
                            <tbody id="targeted-feed-body">
                                <tr>
                                    <td colspan="5" class="text-center text-slate-500 py-10">Loading Targeted Threats...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- BOTSHIELD CONFIG --}}
            <div class="box mt-5">
                <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <h2 class="font-medium text-base mr-auto">
                        BotShield™ Configuration
                    </h2>
                     <button class="btn btn-outline-secondary btn-sm">
                        <i data-lucide="settings" class="w-4 h-4 mr-2"></i> Configure Rules
                    </button>
                </div>
                <div class="p-5 grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="border border-slate-200 rounded-md p-5 dark:border-darkmode-400">
                        <div class="font-medium text-base">Rate Limiting</div>
                        <div class="text-slate-500 text-xs mt-1">Adaptive AI-driven limits</div>
                        <div class="mt-4">
                            <input type="checkbox" id="chk-rate-limit" class="form-check-input border-primary"> 
                            <span class="ml-2 font-medium" id="lbl-rate-limit">Enabled (Strict)</span>
                        </div>
                    </div>
                    <div class="border border-slate-200 rounded-md p-5 dark:border-darkmode-400">
                        <div class="font-medium text-base">Challenge Mode</div>
                        <div class="text-slate-500 text-xs mt-1">JS/Captcha Challenges</div>
                        <div class="mt-4">
                            <input type="checkbox" id="chk-challenge-mode" class="form-check-input border-primary"> 
                            <span class="ml-2 font-medium" id="lbl-challenge-mode">Enabled (Smart)</span>
                        </div>
                    </div>
                    <div class="border border-slate-200 rounded-md p-5 dark:border-darkmode-400">
                        <div class="font-medium text-base">Geo-Fencing</div>
                        <div class="text-slate-500 text-xs mt-1">Block high-risk regions</div>
                        <div class="mt-4">
                            <input type="checkbox" id="chk-geo-fencing" class="form-check-input border-primary"> 
                            <span class="ml-2 font-medium text-slate-500" id="lbl-geo-fencing">Disabled</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT: ANOMALIES & TAKEDOWN (4 COLS) --}}
        <div class="col-span-12 xl:col-span-4 intro-y">
            
            {{-- ANOMALIES LIST --}}
            <div class="box">
                <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <h2 class="font-medium text-base mr-auto">
                        Anomaly Detection
                    </h2>
                </div>
                <div class="p-5" id="anomaly-list">
                    <div class="text-center text-slate-500 py-5">Scanning...</div>
                </div>
                <div class="p-5 border-t border-slate-200/60 dark:border-darkmode-400 text-center">
                     <a href="#" class="text-primary text-xs font-medium">View All Anomalies</a>
                </div>
            </div>

            {{-- TAKEDOWN MONITOR --}}
            <div class="box mt-5 bg-slate-50 dark:bg-darkmode-600 border-none">
                <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <h2 class="font-medium text-base mr-auto">
                        Takedown & Disruption
                    </h2>
                     <div class="w-2 h-2 bg-warning rounded-full"></div>
                </div>
                <div class="p-5">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden bg-white flex items-center justify-center">
                            <img src="https://ui-avatars.com/api/?name=Phishing&background=ef4444&color=fff" class="w-8 h-8 rounded-full">
                        </div>
                        <div class="ml-4 mr-auto">
                            <div class="font-medium">Phishing Campaign #882</div>
                            <div class="text-slate-500 text-xs mt-0.5">Target: /login</div>
                        </div>
                        <div class="text-success text-xs font-medium">REMOVED</div>
                    </div>
                     <div class="flex items-center mb-4">
                        <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden bg-white flex items-center justify-center">
                            <img src="https://ui-avatars.com/api/?name=Copyright&background=f59e0b&color=fff" class="w-8 h-8 rounded-full">
                        </div>
                        <div class="ml-4 mr-auto">
                            <div class="font-medium">Content Scraper Bot</div>
                            <div class="text-slate-500 text-xs mt-0.5">IP: 102.33.22.11</div>
                        </div>
                        <div class="text-warning text-xs font-medium">PENDING</div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden bg-white flex items-center justify-center">
                            <img src="https://ui-avatars.com/api/?name=Malware&background=ef4444&color=fff" class="w-8 h-8 rounded-full">
                        </div>
                        <div class="ml-4 mr-auto">
                            <div class="font-medium">Malware Host C2</div>
                            <div class="text-slate-500 text-xs mt-0.5">Domain: bad-site.com</div>
                        </div>
                        <div class="text-slate-500 text-xs font-medium">QUEUED</div>
                    </div>
                </div>
                 <div class="p-3 border-t border-slate-200/60 dark:border-darkmode-400">
                     <button class="btn btn-outline-danger w-full btn-sm border-dashed">
                        Initiate New Takedown <i data-lucide="zap" class="w-3 h-3 ml-2"></i>
                     </button>
                </div>
            </div>

        </div>
    </div>

    {{-- ROW 3: SECURITY LOGS --}}
    <div class="col-span-12 mt-5">
         <div class="intro-y flex items-center h-10">
             <h2 class="text-lg font-medium truncate mr-5">
                Security Event Logs
            </h2>
        </div>
        <div class="grid grid-cols-12 gap-6 mt-2">
            {{-- ACCESS LOGS --}}
            <div class="col-span-12 md:col-span-6 box intro-y">
                <div class="flex items-center px-5 py-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <h2 class="font-medium text-base mr-auto">
                        Recent Access Attempts
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="table table-hover w-full">
                        <thead>
                            <tr class="bg-slate-50/50 dark:bg-darkmode-600/50">
                                <th class="font-medium px-5 py-3 border-b-2 border-slate-200/60 text-xs uppercase text-slate-500 whitespace-nowrap">Time</th>
                                <th class="font-medium px-5 py-3 border-b-2 border-slate-200/60 text-xs uppercase text-slate-500 whitespace-nowrap">Identity</th>
                                <th class="font-medium px-5 py-3 border-b-2 border-slate-200/60 text-xs uppercase text-slate-500 text-center whitespace-nowrap">Status</th>
                            </tr>
                        </thead>
                        <tbody id="access-logs-body">
                           <tr><td colspan="3" class="text-center py-4 text-slate-500">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ANOMALY LOGS --}}
            <div class="col-span-12 md:col-span-6 box intro-y">
                <div class="flex items-center px-5 py-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <h2 class="font-medium text-base mr-auto">
                        Detected Anomalies
                    </h2>
                </div>
                 <div class="overflow-x-auto">
                    <table class="table table-hover w-full">
                        <thead>
                             <tr class="bg-slate-50/50 dark:bg-darkmode-600/50">
                                <th class="font-medium px-5 py-3 border-b-2 border-slate-200/60 text-xs uppercase text-slate-500 whitespace-nowrap">Time</th>
                                <th class="font-medium px-5 py-3 border-b-2 border-slate-200/60 text-xs uppercase text-slate-500 whitespace-nowrap">Event</th>
                                <th class="font-medium px-5 py-3 border-b-2 border-slate-200/60 text-xs uppercase text-slate-500 text-center whitespace-nowrap">Severity</th>
                            </tr>
                        </thead>
                        <tbody id="anomaly-logs-body">
                             <tr><td colspan="3" class="text-center py-4 text-slate-500">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Helper Render Functions
            const renderAccessRow = (log) => {
                const statusClass = log.status === 'success' ? 'text-success' : 'text-danger';
                return `
                    <tr class="hover:bg-slate-50 dark:hover:bg-darkmode-600">
                        <td class="whitespace-nowrap text-slate-500 text-xs py-3 px-5 border-b border-slate-200/60 dark:border-darkmode-400">${new Date(log.created_at).toLocaleString()}</td>
                        <td class="whitespace-nowrap py-3 px-5 border-b border-slate-200/60 dark:border-darkmode-400">
                            <div class="font-medium">${log.email || 'Unknown'}</div>
                            <div class="text-xs text-slate-500">${log.ip_address}</div>
                        </td>
                         <td class="whitespace-nowrap text-center py-3 px-5 border-b border-slate-200/60 dark:border-darkmode-400">
                            <span class="${statusClass} font-medium uppercase text-xs">${log.status}</span>
                        </td>
                    </tr>
                `;
            }

            const renderAnomalyRow = (log) => {
                let severityClass = 'bg-slate-100 text-slate-600';
                if(log.severity === 'medium') severityClass = 'bg-warning/10 text-warning border border-warning/20';
                if(log.severity === 'high') severityClass = 'bg-danger/10 text-danger border border-danger/20';
                if(log.severity === 'critical') severityClass = 'bg-danger text-white border border-danger';

                return `
                    <tr class="hover:bg-slate-50 dark:hover:bg-darkmode-600">
                         <td class="whitespace-nowrap text-slate-500 text-xs py-3 px-5 border-b border-slate-200/60 dark:border-darkmode-400">${new Date(log.created_at).toLocaleString()}</td>
                         <td class="whitespace-nowrap py-3 px-5 border-b border-slate-200/60 dark:border-darkmode-400">
                            <div class="font-medium text-danger">${log.event_type}</div>
                            <div class="text-xs text-slate-500 truncate w-32" title="${log.details}">${log.details}</div>
                        </td>
                         <td class="whitespace-nowrap text-center py-3 px-5 border-b border-slate-200/60 dark:border-darkmode-400">
                            <span class="px-2 py-1 rounded-full text-xs font-medium border ${severityClass}">${log.severity}</span>
                        </td>
                    </tr>
                `;
            }

            // Function to render attack feed row
            const renderAttackRow = (attack) => {
                return `
                    <tr class="intro-x hover:bg-slate-50 dark:hover:bg-darkmode-600 transition-colors">
                        <td class="px-5 py-4 text-slate-500 border-b border-slate-200/60 dark:border-darkmode-400">
                            ${new Date(attack.created_at).toLocaleTimeString()}
                        </td>
                        <td class="px-5 py-4 border-b border-slate-200/60 dark:border-darkmode-400">
                            <div class="font-medium whitespace-nowrap">${attack.attack_type || 'Unknown Anomaly'}</div>
                            <div class="text-slate-500 text-xs mt-0.5 whitespace-nowrap">${attack.ml_model || 'Heuristic'}</div>
                        </td>
                         <td class="px-5 py-4 border-b border-slate-200/60 dark:border-darkmode-400">
                            <div class="flex items-center">
                                <div class="w-2 h-2 rounded-full bg-danger mr-2"></div>
                                <span class="font-medium">${attack.source_ip}</span>
                            </div>
                             <div class="text-slate-500 text-xs mt-0.5 ml-4">${attack.source_country || 'Unknown'}</div>
                        </td>
                         <td class="px-5 py-4 border-b border-slate-200/60 dark:border-darkmode-400 text-slate-500">
                            ${attack.destination_ip || 'System'}
                        </td>
                         <td class="px-5 py-4 border-b border-slate-200/60 dark:border-darkmode-400 text-center">
                            <span class="text-xs font-bold uppercase text-danger bg-white dark:bg-darkmode-400 px-2 py-1 rounded border border-danger/20 min-w-[80px] inline-block">Blocked</span>
                        </td>
                    </tr>
                `;
            }

            // Function to render anomaly item
            const renderAnomalyItem = (anomaly) => {
                return `
                    <div class="flex items-center mb-4 last:mb-0">
                        <div class="w-2 h-2 bg-warning rounded-full mr-3"></div>
                        <div class="flex-1">
                            <div class="font-medium">${anomaly.block_reason || 'Suspicious Activity'}</div>
                            <div class="text-slate-500 text-xs mt-0.5">${anomaly.ip_address} &bull; ${new Date(anomaly.created_at).toLocaleTimeString()}</div>
                        </div>
                    </div>
                `;
            }

            // Render Targeted Row
            const renderTargetedRow = (attack) => {
                 let severityClass = 'bg-slate-100 text-slate-600 border-slate-200';
                 if(attack.severity === 'critical') severityClass = 'bg-danger/10 text-danger border-danger/20';
                 else if(attack.severity === 'high') severityClass = 'bg-warning/10 text-warning border-warning/20';
                 else if(attack.severity === 'medium') severityClass = 'bg-primary/10 text-primary border-primary/20';

                 return `
                    <tr class="intro-x hover:bg-slate-50 dark:hover:bg-darkmode-600 transition-colors">
                        <td class="px-5 py-4 text-slate-500 border-b border-slate-200/60 dark:border-darkmode-400">
                            ${new Date(attack.incident_at).toLocaleTimeString()}
                        </td>
                         <td class="px-5 py-4 font-medium border-b border-slate-200/60 dark:border-darkmode-400 text-primary">
                            ${attack.target_url || '/'}
                        </td>
                        <td class="px-5 py-4 border-b border-slate-200/60 dark:border-darkmode-400">
                             <div class="font-medium whitespace-nowrap">${attack.attack_vector}</div>
                             <div class="text-slate-500 text-xs mt-0.5 whitespace-nowrap">${attack.affected_asset || '-'}</div>
                        </td>
                         <td class="px-5 py-4 border-b border-slate-200/60 dark:border-darkmode-400">
                             <div class="flex items-center">
                                <div class="px-2.5 py-1 rounded-full text-xs font-medium w-fit border lowercase first-letter:uppercase ${severityClass}">
                                    ${attack.severity}
                                </div>
                             </div>
                        </td>
                         <td class="px-5 py-4 border-b border-slate-200/60 dark:border-darkmode-400 text-center">
                            <span class="text-xs font-bold uppercase ${attack.status === 'blocked' ? 'text-success' : 'text-warning'} bg-slate-100 dark:bg-darkmode-400 px-2 py-1 rounded border border-slate-200 dark:border-darkmode-300 min-w-[80px] inline-block">${attack.status}</span>
                        </td>
                    </tr>
                `;
            }

            // Settings Handlers
            const updateSetting = (key, value, labelId, activeText) => {
                axios.post('/security-center/update-settings', { key, value })
                    .then(res => {
                         const lbl = document.getElementById(labelId);
                         if(value === 'true') {
                             lbl.innerText = activeText;
                             lbl.className = 'ml-2 font-medium';
                         } else {
                             lbl.innerText = 'Disabled';
                             lbl.className = 'ml-2 font-medium text-slate-500';
                         }
                    })
                    .catch(e => alert('Failed to save setting'));
            }

            const initSettingsListeners = () => {
                document.getElementById('chk-rate-limit').addEventListener('change', (e) => {
                    updateSetting('rate_limiting', e.target.checked ? 'true' : 'false', 'lbl-rate-limit', 'Enabled (Strict)');
                });
                document.getElementById('chk-challenge-mode').addEventListener('change', (e) => {
                    updateSetting('challenge_mode', e.target.checked ? 'true' : 'false', 'lbl-challenge-mode', 'Enabled (Smart)');
                });
                 document.getElementById('chk-geo-fencing').addEventListener('change', (e) => {
                    updateSetting('geo_fencing', e.target.checked ? 'true' : 'false', 'lbl-geo-fencing', 'Enabled (High Risk)');
                });
            }
            initSettingsListeners();

            // Resource Charts Initialization
            let cpuChartInstance = null;
            let ramChartInstance = null;
            let diskChartInstance = null;

            const createCircleChart = (id, color) => {
                 const ctx = document.getElementById(id).getContext('2d');
                 return new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Used', 'Free'],
                        datasets: [{
                            data: [0, 100],
                            backgroundColor: [color, '#e2e8f0'], // #e2e8f0 is slate-200
                            borderWidth: 0,
                            hoverOffset: 0
                        }]
                    },
                    options: {
                        cutout: '80%',
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false }, tooltip: { enabled: false } },
                        animation: { duration: 0 }
                    }
                 });
            }

            const initResourceCharts = () => {
                cpuChartInstance = createCircleChart('cpu-chart', '#1e40af'); // primary
                ramChartInstance = createCircleChart('ram-chart', '#f59e0b'); // warning
                diskChartInstance = createCircleChart('disk-chart', '#10b981'); // success
            }
            initResourceCharts();

            // Chart Initialization
            let trafficChartInstance = null;
            const initTrafficChart = () => {
                const ctx = document.getElementById('traffic-chart').getContext('2d');
                trafficChartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: [],
                        datasets: [
                            {
                                label: 'Inbound (Mbps)',
                                data: [],
                                borderColor: '#1e40af', // Primary
                                backgroundColor: 'rgba(30, 64, 175, 0.1)',
                                tension: 0.4,
                                fill: true,
                                pointRadius: 0
                            },
                             {
                                label: 'Outbound (Mbps)',
                                data: [],
                                borderColor: '#f59e0b', // Warning
                                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                                tension: 0.4,
                                fill: true,
                                pointRadius: 0,
                                borderDash: [5, 5]
                            }
                        ]
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { display: false },
                            y: { display: true, beginAtZero: true, grid: { borderDash: [2, 2], drawBorder: false } }
                        },
                        animation: { duration: 0 } 
                    }
                });
            }
            initTrafficChart();

            // Fetch Data
            const loadData = () => {
                axios.get('/security-center/stats')
                    .then(res => {
                        const d = res.data.data;
                        
                        // 1. Stats
                        document.getElementById('stat-botnets').innerText = d.botshield.active_botnets;
                        document.getElementById('stat-c2').innerText = d.botshield.c2_nodes_blocked;
                        document.getElementById('stat-traffic').innerText = d.botshield.traffic_scrubbed;
                        document.getElementById('stat-threat').innerText = d.threat_level;

                        // 2. Feed
                        const attacks = d.attacks.recent;
                        if(attacks.length > 0) {
                            document.getElementById('attack-feed-body').innerHTML = attacks.map(renderAttackRow).join('');
                        } else {
                             document.getElementById('attack-feed-body').innerHTML = '<tr><td colspan="5" class="text-center py-4 text-slate-500">No active threats detected.</td></tr>';
                        }

                        // 3. Targeted Feed
                        const targeted = d.targeted_attacks || [];
                        if(targeted.length > 0) {
                             document.getElementById('targeted-feed-body').innerHTML = targeted.map(renderTargetedRow).join('');
                        } else {
                             document.getElementById('targeted-feed-body').innerHTML = '<tr><td colspan="5" class="text-center py-4 text-slate-500">No targeted threats detected.</td></tr>';
                        }

                        // 4. Anomalies
                         const anomalies = d.anomalies.recent;
                        if(anomalies.length > 0) {
                            document.getElementById('anomaly-list').innerHTML = anomalies.map(renderAnomalyItem).join('');
                        } else {
                             document.getElementById('anomaly-list').innerHTML = '<div class="text-center text-slate-500">System Nominal</div>';
                        }

                         // 5. Server Health
                         const health = d.server_health || {};
                         const current = health.current || {};
                         
                         // Resources (Circular Updates)
                         const cpuVal = Math.round(current.cpu_usage || 0);
                         const ramVal = Math.round(current.memory_usage || 0);
                         const diskVal = Math.round(current.disk_usage || 0);

                         document.getElementById('cpu-val-center').innerText = cpuVal + '%';
                         document.getElementById('ram-val-center').innerText = ramVal + '%';
                         document.getElementById('disk-val-center').innerText = diskVal + '%';

                         if(cpuChartInstance) {
                             cpuChartInstance.data.datasets[0].data = [cpuVal, 100-cpuVal];
                             cpuChartInstance.update();
                         }
                         if(ramChartInstance) {
                             ramChartInstance.data.datasets[0].data = [ramVal, 100-ramVal];
                             ramChartInstance.update();
                         }
                         if(diskChartInstance) {
                             diskChartInstance.data.datasets[0].data = [diskVal, 100-diskVal];
                             diskChartInstance.update();
                         }

                         document.getElementById('server-status-text').innerText = (current.status || 'Unknown').toUpperCase();
                         
                         // Traffic Values
                         document.getElementById('traffic-in-val').innerText = (current.traffic_in || 0) + ' Mbps';
                         document.getElementById('traffic-out-val').innerText = (current.traffic_out || 0) + ' Mbps';

                         // Chart
                         const history = health.history || [];
                         if(trafficChartInstance && history.length > 0) {
                             trafficChartInstance.data.labels = history.map(h => new Date(h.created_at).toLocaleTimeString());
                             trafficChartInstance.data.datasets[0].data = history.map(h => h.traffic_in);
                             trafficChartInstance.data.datasets[1].data = history.map(h => h.traffic_out);
                             trafficChartInstance.update();
                         }

                        // 6. Security Events
                         const events = d.security_events || {};
                         const accessLogs = events.access_logs || [];
                         const serverAnomalies = events.anomalies || [];

                         if(accessLogs.length > 0){
                             document.getElementById('access-logs-body').innerHTML = accessLogs.map(renderAccessRow).join('');
                         } else {
                              document.getElementById('access-logs-body').innerHTML = '<tr><td colspan="3" class="text-center py-4 text-slate-500">No recent logs.</td></tr>';
                         }

                         if(serverAnomalies.length > 0){
                             document.getElementById('anomaly-logs-body').innerHTML = serverAnomalies.map(renderAnomalyRow).join('');
                         } else {
                              document.getElementById('anomaly-logs-body').innerHTML = '<tr><td colspan="3" class="text-center py-4 text-slate-500">No anomalies detected.</td></tr>';
                         }

                        // 7. Settings
                        const settings = d.settings || {};
                        const setChk = (id, key, labelId, activeText) => {
                            const val = settings[key] === 'true';
                            const el = document.getElementById(id);
                            if(el) {
                                el.checked = val;
                                const lbl = document.getElementById(labelId);
                                if(val) {
                                    lbl.innerText = activeText;
                                    lbl.className = 'ml-2 font-medium';
                                } else {
                                    lbl.innerText = 'Disabled';
                                    lbl.className = 'ml-2 font-medium text-slate-500';
                                }
                            }
                        };
                        
                        setChk('chk-rate-limit', 'rate_limiting', 'lbl-rate-limit', 'Enabled (Strict)');
                        setChk('chk-challenge-mode', 'challenge_mode', 'lbl-challenge-mode', 'Enabled (Smart)');
                        setChk('chk-geo-fencing', 'geo_fencing', 'lbl-geo-fencing', 'Enabled (High Risk)');
                    })
                    .catch(e => console.error("Security Center Error", e));
            }

            loadData();
            // Poll every 10 seconds for "Live" feel
            setInterval(loadData, 10000);
        });
    </script>
@endpush
