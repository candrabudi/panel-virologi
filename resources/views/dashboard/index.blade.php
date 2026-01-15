@extends('layouts.app')
@section('title', 'Data Analytics')
@section('content')
    <div class="col-span-12">
        <div class="flex flex-col gap-y-10">
            
            {{-- HEADER: GENERAL REPORT STYLE --}}
            <div class="flex flex-col gap-y-3 md:h-10 md:flex-row md:items-center">
                <div class="text-base font-medium group-[.mode--light]:text-white">
                    General Dashboard
                </div>
                <div class="flex flex-col gap-x-3 gap-y-2 md:ml-auto md:flex-row">
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-primary/10 text-primary group-[.mode--light]:bg-white/20 group-[.mode--light]:text-white rounded-lg text-sm font-medium border border-primary/20 group-[.mode--light]:border-white/10">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                        <span id="current-date">Today, 15 Jan 2026</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-6">
                
                {{-- ROW 1: REPORT BOXES (Standard Tailwise) --}}
                <div class="col-span-12 sm:col-span-6 md:col-span-3">
                    <div class="box p-5 relative overflow-hidden border-t-4 border-primary">
                        <div class="flex items-center">
                            <div class="block">
                                <div class="text-slate-500 text-xs uppercase font-bold tracking-wider">AI Tokens</div>
                                <div id="total-tokens" class="text-2xl font-medium leading-8 mt-1">0</div>
                            </div>
                            <div class="ml-auto">
                                <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                                    <i data-lucide="zap" class="w-5 h-5"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center">
                             <div id="growth-badge" class="flex items-center text-success text-xs font-medium hidden">
                                <i data-lucide="chevron-up" class="w-4 h-4"></i>
                                <span>+0%</span>
                             </div>
                             <div class="text-slate-400 text-xs ml-auto">vs last 30 days</div>
                        </div>
                    </div>
                </div>

                <div class="col-span-12 sm:col-span-6 md:col-span-3">
                    <div class="box p-5 relative overflow-hidden border-t-4 border-danger">
                        <div class="flex items-center">
                            <div class="block">
                                <div class="text-slate-500 text-xs uppercase font-bold tracking-wider">Threats Blocked</div>
                                <div id="total-attacks" class="text-2xl font-medium leading-8 mt-1">0</div>
                            </div>
                            <div class="ml-auto">
                                <div class="w-10 h-10 rounded-full bg-danger/10 text-danger flex items-center justify-center">
                                    <i data-lucide="shield" class="w-5 h-5"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center">
                            <div class="text-danger flex items-center text-xs font-medium">
                                <span class="w-2 h-2 rounded-full bg-danger mr-1.5"></span>
                                Live
                            </div>
                            <div class="text-slate-400 text-xs ml-auto">Real-time protection</div>
                       </div>
                    </div>
                </div>

                <div class="col-span-12 sm:col-span-6 md:col-span-3">
                    <div class="box p-5 relative overflow-hidden border-t-4 border-pending">
                        <div class="flex items-center">
                            <div class="block">
                                <div class="text-slate-500 text-xs uppercase font-bold tracking-wider">Data Leaks</div>
                                <div id="total-leaks" class="text-2xl font-medium leading-8 mt-1">0</div>
                            </div>
                            <div class="ml-auto">
                                <div class="w-10 h-10 rounded-full bg-pending/10 text-pending flex items-center justify-center">
                                    <i data-lucide="eye" class="w-5 h-5"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center">
                            <div class="text-pending flex items-center text-xs font-medium">
                                <i data-lucide="search" class="w-3 h-3 mr-1"></i>
                                Verified
                            </div>
                            <div class="text-slate-400 text-xs ml-auto">Deep web scans</div>
                       </div>
                    </div>
                </div>

                <div class="col-span-12 sm:col-span-6 md:col-span-3">
                    <div class="box p-5 relative overflow-hidden border-t-4 border-success">
                        <div class="flex items-center">
                            <div class="block">
                                <div class="text-slate-500 text-xs uppercase font-bold tracking-wider">Integrity</div>
                                <div id="success-rate" class="text-2xl font-medium leading-8 mt-1">100%</div>
                            </div>
                            <div class="ml-auto">
                                <div class="w-10 h-10 rounded-full bg-success/10 text-success flex items-center justify-center">
                                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center">
                            <div class="w-full bg-slate-100 rounded-full h-1.5 dark:bg-darkmode-400">
                                <div class="w-full h-full bg-success rounded-full"></div>
                            </div>
                       </div>
                    </div>
                </div>

                {{-- ROW 2: MAIN CHART & SIDEBAR --}}
                
                {{-- ROW 2: MAIN TRAFFIC & THREAT LIST --}}
                
                {{-- LEFT: TRAFFIC CHART (8 cols) --}}
                <div class="col-span-12 xl:col-span-8">
                    <div class="box p-5 h-full border-t-4 border-primary/50">
                        <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                            <h2 class="font-medium text-base mr-auto">Neural Traffic Analysis</h2>
                            <div class="flex items-center gap-3">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-primary rounded-full mr-2"></div>
                                    <span class="text-slate-500 text-xs">Tokens Usage</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-slate-300 rounded-full mr-2"></div>
                                    <span class="text-slate-500 text-xs">Requests</span>
                                </div>
                            </div>
                        </div>
                        <div class="h-[350px]">
                            <canvas id="traffic-chart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- RIGHT: THREAT LIST (4 cols) --}}
                <div class="col-span-12 xl:col-span-4">
                    <div class="box p-5 h-full border-t-4 border-danger/50 flex flex-col">
                        <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                            <h2 class="font-medium text-base mr-auto">Threat Geography</h2>
                             <button class="text-slate-500 hover:text-primary">
                                <i data-lucide="more-horizontal" class="w-5 h-5"></i>
                            </button>
                        </div>
                        <div id="top-countries-list" class="flex flex-col gap-4 flex-1">
                            {{-- Placeholder --}}
                            <div class="text-center py-10 text-slate-400 italic text-xs">Loading data...</div>
                        </div>
                        <a href="#" class="btn btn-outline-secondary w-full border-slate-300 dark:border-darkmode-300 border-dashed text-slate-500 mt-5">View Full Report</a>
                    </div>
                </div>

                {{-- ROW 3: SECURITY CHART & ECOSYSTEM --}}

                {{-- LEFT: SECURITY LOAD (6 cols) --}}
                <div class="col-span-12 xl:col-span-6">
                    <div class="box p-5 h-full border-t-4 border-slate-400/50">
                        <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                            <h2 class="font-medium text-base mr-auto">Security Load Analysis</h2>
                             <div class="flex items-center gap-3">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-danger rounded-full mr-2"></div>
                                    <span class="text-slate-500 text-xs">Blocked</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-success rounded-full mr-2"></div>
                                    <span class="text-slate-500 text-xs">Unique IPs</span>
                                </div>
                            </div>
                        </div>
                        <div class="h-[320px]">
                            <canvas id="security-chart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- RIGHT: ECOSYSTEM (6 cols) --}}
                <div class="col-span-12 xl:col-span-6">
                    <div class="grid grid-cols-2 gap-6 h-full">
                        <div class="col-span-1">
                            <div class="box p-5 h-full flex flex-col justify-center">
                                <div class="flex items-start">
                                    <div class="w-full">
                                        <div class="text-lg font-medium truncate mr-3" id="stat-articles">0</div>
                                        <div class="text-slate-500 mt-1">Total Articles</div>
                                    </div>
                                    <div class="flex-none ml-auto relative">
                                        <i data-lucide="file-text" class="w-6 h-6 text-slate-500"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-1">
                            <div class="box p-5 h-full flex flex-col justify-center">
                                <div class="flex items-start">
                                    <div class="w-full">
                                        <div class="text-lg font-medium truncate mr-3" id="stat-ebooks">0</div>
                                        <div class="text-slate-500 mt-1">Published E-Books</div>
                                    </div>
                                    <div class="flex-none ml-auto relative">
                                        <i data-lucide="book" class="w-6 h-6 text-slate-500"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-1">
                            <div class="box p-5 h-full flex flex-col justify-center">
                                <div class="flex items-start">
                                    <div class="w-full">
                                        <div class="text-lg font-medium truncate mr-3" id="stat-products">0</div>
                                        <div class="text-slate-500 mt-1">Active Products</div>
                                    </div>
                                    <div class="flex-none ml-auto relative">
                                        <i data-lucide="package" class="w-6 h-6 text-slate-500"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-1">
                            <div class="box p-5 h-full flex flex-col justify-center">
                                <div class="flex items-start">
                                    <div class="w-full">
                                        <div class="text-lg font-medium truncate mr-3" id="stat-users-total">0</div>
                                        <div class="text-slate-500 mt-1">Registered Users</div>
                                    </div>
                                    <div class="flex-none ml-auto relative">
                                        <i data-lucide="users" class="w-6 h-6 text-slate-500"></i>
                                    </div>
                                </div>
                                <div class="mt-3 flex gap-2">
                                    <div class="py-1 px-2 rounded-md bg-slate-100 dark:bg-darkmode-400 text-xs font-medium text-slate-500">
                                        <span id="stat-users-admin">0</span> Adm
                                    </div>
                                    <div class="py-1 px-2 rounded-md bg-slate-100 dark:bg-darkmode-400 text-xs font-medium text-slate-500">
                                        <span id="stat-users-editor">0</span> Edt
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('dist/js/vendors/chartjs.js') }}"></script>
    <script src="{{ asset('dist/js/utils/colors.js') }}"></script>
    <script src="{{ asset('dist/js/utils/helper.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // FETCH ANALYTICS DATA
        axios.get('/dashboard/summary')
            .then(res => {
                const d = res.data.data
                
                // 1. Core Summary Metrics
                document.getElementById('total-tokens').innerText = (d.ai.total_units || 0).toLocaleString()
                document.getElementById('total-attacks').innerText = (d.site.cyber_attacks?.total || 0).toLocaleString()
                document.getElementById('total-leaks').innerText = (d.site.leak_checks?.total || 0).toLocaleString()
                document.getElementById('success-rate').innerText = (d.ai.success_rate || 0) + '%'
                
                // Growth Badge Logic
                if (d.ai.growth !== undefined) {
                    const growthBadge = document.getElementById('growth-badge')
                    const isPositive = d.ai.growth >= 0
                    growthBadge.innerText = (isPositive ? '+' : '') + d.ai.growth + '%'
                    growthBadge.className = `px-2 py-0.5 border rounded text-[10px] font-bold ${isPositive ? 'bg-success/20 border-success/30 text-success' : 'bg-danger/20 border-danger/30 text-danger'}`
                    growthBadge.classList.remove('hidden')
                }

                // Update Date
                const now = new Date()
                const dateOptions = { weekday: 'long', year: 'numeric', month: 'short', day: 'numeric' }
                document.getElementById('current-date').innerText = now.toLocaleDateString('en-GB', dateOptions)

                // 2. Security Breakdown (Threat Geography) - Standard List
                const countriesList = document.getElementById('top-countries-list')
                if (d.security?.top_countries?.length > 0) {
                    countriesList.innerHTML = d.security.top_countries.map(c => {
                        const countryName = c.country || 'Unknown'
                        // Simple clean list item
                        return `
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 rounded-full bg-danger"></div>
                            <div class="flex-1">
                                <div class="font-medium text-slate-700 dark:text-slate-300 text-sm">${countryName}</div>
                                <div class="text-xs text-slate-500 mt-0.5">Source Origin</div>
                            </div>
                            <div class="text-right">
                                <div class="font-medium text-slate-700 dark:text-slate-300">${c.count.toLocaleString()}</div>
                                <div class="text-xs text-slate-500 mt-0.5 text-danger">High Risk</div>
                            </div>
                        </div>
                    `}).join('')
                } else {
                    countriesList.innerHTML = '<div class="text-slate-400 text-center py-4">No threats detected</div>'
                }

                // 3. Ecosystem Metrics
                document.getElementById('stat-articles').innerText = d.site.total_articles.toLocaleString()
                document.getElementById('stat-ebooks').innerText = d.site.total_ebooks.toLocaleString()
                document.getElementById('stat-products').innerText = d.site.total_products.toLocaleString()
                
                // User Stats
                document.getElementById('stat-users-total').innerText = d.site.total_users.total.toLocaleString()
                document.getElementById('stat-users-admin').innerText = d.site.total_users.admin.toLocaleString()
                document.getElementById('stat-users-editor').innerText = d.site.total_users.editor.toLocaleString()

                if (typeof lucide !== 'undefined') {
                    lucide.createIcons()
                }
            })
            .catch(err => console.error('Dashboard Engine Critical Error:', err))

        // INITIALIZE CHARTS
        document.addEventListener("DOMContentLoaded", function() {
            const chartTrafficEl = document.querySelector("#traffic-chart");
            const chartSecurityEl = document.querySelector("#security-chart");

            if (chartTrafficEl) {
                axios.get('/dashboard/ai-traffic-daily')
                    .then(res => {
                        const { labels, series } = res.data.data;
                        const ctxTraffic = chartTrafficEl.getContext("2d");
                        const ctxSecurity = chartSecurityEl ? chartSecurityEl.getContext("2d") : null;

                        const gradientPrimary = () => {
                            const g = ctxTraffic.createLinearGradient(0, 0, 0, 300);
                            g.addColorStop(0, getColor("primary", 0.2));
                            g.addColorStop(1, document.querySelector("html").classList.contains("dark") ? "#28344e00" : "#ffffff01");
                            return g;
                        };

                        // 1. Traffic Chart (Line)
                        const trafficChart = new Chart(ctxTraffic, {
                            type: "line",
                            data: {
                                labels: labels,
                                datasets: [
                                    {
                                        label: "AI Tokens",
                                        data: series.tokens,
                                        borderWidth: 2,
                                        borderColor: getColor("primary", 0.8),
                                        backgroundColor: gradientPrimary(),
                                        fill: true,
                                        tension: 0.4,
                                        pointRadius: 0,
                                        pointHoverRadius: 5
                                    },
                                    {
                                        label: "Requests",
                                        data: series.requests,
                                        borderWidth: 1,
                                        borderColor: getColor("slate.400", 0.5),
                                        borderDash: [5, 5],
                                        backgroundColor: "transparent",
                                        fill: false,
                                        tension: 0.4,
                                        pointRadius: 0
                                    }
                                ]
                            },
                            options: {
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: { mode: 'index', intersect: false }
                                },
                                scales: {
                                    x: { grid: { display: false } },
                                    y: { grid: { color: getColor("slate.200", 0.6) } }
                                }
                            }
                        });


                        // 2. Security Chart (Bar)
                        if (chartSecurityEl) {
                             new Chart(ctxSecurity, {
                                type: "bar",
                                data: {
                                    labels: labels,
                                    datasets: [
                                        {
                                            label: "Unique IPs",
                                            data: series.unique_ips,
                                            backgroundColor: getColor("success", 0.9),
                                            borderRadius: 2,
                                            barPercentage: 0.6
                                        },
                                        {
                                            label: "Blocked Incidents",
                                            data: series.blocked,
                                            backgroundColor: getColor("danger", 0.9),
                                            borderRadius: 2,
                                            barPercentage: 0.6
                                        }
                                    ]
                                },
                                options: {
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: { display: false },
                                        tooltip: { mode: 'index', intersect: false }
                                    },
                                    scales: {
                                        x: { grid: { display: false }, stacked: true },
                                        y: { grid: { color: getColor("slate.200", 0.6) }, stacked: true }
                                    }
                                }
                            });
                        }

                        // Resize watcher
                        helper.watchCssVariables("html", ["color-primary"], () => {
                            trafficChart.data.datasets[0].borderColor = getColor("primary", 0.8);
                            trafficChart.data.datasets[0].backgroundColor = gradientPrimary();
                            trafficChart.update();
                        });
                    })
                    .catch(e => console.error("Dashboard Chart Error:", e));
            }
        });
    </script>
@endpush
