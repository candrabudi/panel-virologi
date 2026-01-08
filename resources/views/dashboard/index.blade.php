@extends('layouts.app')
@section('title', 'Data Analytics')
@section('content')
    <div class="col-span-12">
        <div>
            <div class="flex flex-col gap-y-3 md:h-10 md:flex-row md:items-center">
                <div class="text-base font-medium group-[.mode--light]:text-white">
                    Data Analytics Virologi
                </div>
            </div>

            {{-- AI SUMMARY CARDS --}}
            <div class="tab-content box box--stacked mt-3.5">
                <div class="tab-pane active flex flex-col gap-2 p-1.5 leading-relaxed xl:flex-row">
                    <div class="grid w-full grid-cols-4 gap-2">
                        <div class="box relative col-span-4 flex-1 overflow-hidden rounded-[0.6rem] border-0 bg-slate-50 bg-gradient-to-b from-theme-2/90 to-theme-1/[0.85] p-5 sm:col-span-2 xl:col-span-1">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full border border-white/10 bg-white/10">
                                <i data-lucide="zap" class="stroke-[1] h-6 w-6 fill-white/10 text-white"></i>
                            </div>
                            <div class="mt-12 flex items-center">
                                <div id="total-tokens" class="text-2xl font-medium text-white">0</div>
                                <div id="growth-badge" class="ml-2 px-2 py-0.5 bg-white/20 rounded text-[10px] text-white font-bold hidden">
                                    +0%
                                </div>
                            </div>
                            <div class="mt-1 text-base text-white/70">
                                Total AI Tokens
                            </div>
                        </div>
                        <div class="relative col-span-4 flex-1 overflow-hidden rounded-[0.6rem] border bg-slate-50/50 p-5 sm:col-span-2 xl:col-span-1 dark:bg-darkmode-600">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full border border-primary/10 bg-primary/10">
                                <i data-lucide="activity" class="stroke-[1] h-6 w-6 fill-primary/10 text-primary"></i>
                            </div>
                            <div class="mt-12 flex items-center">
                                <div id="total-requests" class="text-2xl font-medium text-slate-700 dark:text-slate-200">0</div>
                            </div>
                            <div class="mt-1 text-base text-slate-500">
                                Total AI Requests
                            </div>
                        </div>
                        <div class="relative col-span-4 flex-1 overflow-hidden rounded-[0.6rem] border bg-slate-50/50 p-5 sm:col-span-2 xl:col-span-1 dark:bg-darkmode-600">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full border border-info/10 bg-info/10">
                                <i data-lucide="globe" class="stroke-[1] h-6 w-6 fill-info/10 text-info"></i>
                            </div>
                            <div class="mt-12 flex items-center">
                                <div id="active-ips" class="text-2xl font-medium text-slate-700 dark:text-slate-200">0</div>
                            </div>
                            <div class="mt-1 text-base text-slate-500">
                                Active IP Addresses
                            </div>
                        </div>
                        <div class="relative col-span-4 flex-1 overflow-hidden rounded-[0.6rem] border bg-slate-50/50 p-5 sm:col-span-2 xl:col-span-1 dark:bg-darkmode-600">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full border border-success/10 bg-success/10">
                                <i data-lucide="shield-check" class="stroke-[1] h-6 w-6 fill-success/10 text-success"></i>
                            </div>
                            <div class="mt-12 flex items-center">
                                <div id="success-rate" class="text-2xl font-medium text-slate-700 dark:text-slate-200">0%</div>
                            </div>
                            <div class="mt-1 text-base text-slate-500">
                                Prompt Success Rate
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TRAFFIC CHART --}}
            <div class="box box--stacked mt-3.5 p-5">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="sm:mr-auto">
                        <div class="text-base text-slate-500 font-medium">
                           AI Traffic Overview (30 Days)
                        </div>
                        <div class="flex items-center mt-1">
                            <div class="text-[10px] text-slate-400 uppercase tracking-widest font-bold">Synchronized Neural Feed</div>
                        </div>
                    </div>
                </div>
                <div class="mt-6">
                    <div class="w-full h-[350px]">
                        <canvas id="traffic-chart"></canvas>
                    </div>
                </div>
            </div>

            {{-- GENERAL SITE STATS (Bento Style within Tailwise) --}}
            <div class="mt-8 flex flex-col gap-y-3">
                <div class="text-base font-medium text-slate-600 dark:text-slate-200">
                    Site Content Overview
                </div>
                <div class="grid grid-cols-12 gap-6">
                    <div class="col-span-12 sm:col-span-6 xl:col-span-2">
                        <div class="box p-5 border-none shadow-sm dark:bg-darkmode-600">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-primary/5 rounded-lg flex items-center justify-center text-primary">
                                    <i data-lucide="file-text" class="w-5 h-5 stroke-[1.5]"></i>
                                </div>
                                <div class="ml-auto text-slate-400">
                                    <i data-lucide="more-vertical" class="w-4 h-4"></i>
                                </div>
                            </div>
                            <div class="mt-6">
                                <div id="stat-articles" class="text-2xl font-bold dark:text-slate-200">0</div>
                                <div class="text-xs text-slate-500 font-medium mt-1">Articles Published</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-6 xl:col-span-2">
                        <div class="box p-5 border-none shadow-sm dark:bg-darkmode-600">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-500/5 rounded-lg flex items-center justify-center text-blue-500">
                                    <i data-lucide="book" class="w-5 h-5 stroke-[1.5]"></i>
                                </div>
                                <div class="ml-auto text-slate-400">
                                    <i data-lucide="more-vertical" class="w-4 h-4"></i>
                                </div>
                            </div>
                            <div class="mt-6">
                                <div id="stat-ebooks" class="text-2xl font-bold dark:text-slate-200">0</div>
                                <div class="text-xs text-slate-500 font-medium mt-1">E-Books Registry</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-6 xl:col-span-2">
                        <div class="box p-5 border-none shadow-sm dark:bg-darkmode-600">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-amber-500/5 rounded-lg flex items-center justify-center text-amber-500">
                                    <i data-lucide="package" class="w-5 h-5 stroke-[1.5]"></i>
                                </div>
                                <div class="ml-auto text-slate-400">
                                    <i data-lucide="more-vertical" class="w-4 h-4"></i>
                                </div>
                            </div>
                            <div class="mt-6">
                                <div id="stat-products" class="text-2xl font-bold dark:text-slate-200">0</div>
                                <div class="text-xs text-slate-500 font-medium mt-1">Total Products</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-6 xl:col-span-2">
                        <div class="box p-5 border-none shadow-sm dark:bg-darkmode-600">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-emerald-500/5 rounded-lg flex items-center justify-center text-emerald-500">
                                    <i data-lucide="award" class="w-5 h-5 stroke-[1.5]"></i>
                                </div>
                                <div class="ml-auto text-slate-400">
                                    <i data-lucide="more-vertical" class="w-4 h-4"></i>
                                </div>
                            </div>
                            <div class="mt-6">
                                <div id="stat-services" class="text-2xl font-bold dark:text-slate-200">0</div>
                                <div class="text-xs text-slate-500 font-medium mt-1">Active Services</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-6 xl:col-span-2">
                        <div class="box p-5 border-none shadow-sm dark:bg-darkmode-600">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-rose-500/5 rounded-lg flex items-center justify-center text-rose-500">
                                    <i data-lucide="users" class="w-5 h-5 stroke-[1.5]"></i>
                                </div>
                                <div class="ml-auto text-slate-400">
                                    <i data-lucide="more-vertical" class="w-4 h-4"></i>
                                </div>
                            </div>
                            <div class="mt-6">
                                <div id="stat-users" class="text-2xl font-bold dark:text-slate-200">0</div>
                                <div class="text-xs text-slate-500 font-medium mt-1">Registered Users</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-6 xl:col-span-2">
                        <div class="box p-5 border-none shadow-sm bg-primary text-white">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                    <i data-lucide="message-square" class="w-5 h-5 stroke-[2]"></i>
                                </div>
                            </div>
                            <div class="mt-6">
                                <div id="stat-chat-sessions" class="text-2xl font-bold">0</div>
                                <div class="text-xs text-white/70 font-medium mt-1">Chat Sessions</div>
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
        // FETCH DATA
        axios.get('/dashboard/summary')
            .then(res => {
                const d = res.data
                
                // AI Stats
                document.getElementById('total-tokens').innerText = (d.total_units || 0).toLocaleString()
                document.getElementById('total-requests').innerText = (d.total_requests || 0).toLocaleString()
                document.getElementById('active-ips').innerText = (d.active_ips || 0).toLocaleString()
                document.getElementById('success-rate').innerText = (d.success_rate || 0) + '%'
                
                if (d.growth !== undefined) {
                    const growthBadge = document.getElementById('growth-badge')
                    growthBadge.innerText = (d.growth >= 0 ? '+' : '') + d.growth + '%'
                    growthBadge.classList.remove('hidden')
                }

                // Site Stats
                if (d.site) {
                    document.getElementById('stat-articles').innerText = d.site.total_articles.toLocaleString()
                    document.getElementById('stat-ebooks').innerText = d.site.total_ebooks.toLocaleString()
                    document.getElementById('stat-products').innerText = d.site.total_products.toLocaleString()
                    document.getElementById('stat-users').innerText = d.site.total_users.toLocaleString()
                    document.getElementById('stat-services').innerText = d.site.total_services.toLocaleString()
                    document.getElementById('stat-chat-sessions').innerText = d.site.total_chat_sessions.toLocaleString()
                }
                
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons()
                }
            })
            .catch(err => console.error('Gagal memuat dashboard nodes', err))

        // INITIALIZE CHART
        $(function() {
            const chartEl = $("#traffic-chart");
            if (!chartEl.length) return;

            axios.get('/dashboard/ai-traffic-daily')
                .then(res => {
                    const { labels, series } = res.data;
                    const ctx = chartEl[0].getContext("2d");

                    const gradient = () => {
                        const g = ctx.createLinearGradient(0, 0, 0, 300);
                        g.addColorStop(0, getColor("primary", 0.2));
                        g.addColorStop(1, $("html").hasClass("dark") ? "#28344e00" : "#ffffff01");
                        return g;
                    };

                    const chart = new Chart(ctx, {
                        type: "line",
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: "AI Tokens",
                                    data: series.tokens,
                                    borderWidth: 2,
                                    borderColor: getColor("primary", 0.8),
                                    backgroundColor: gradient(),
                                    fill: true,
                                    tension: 0.4,
                                    pointRadius: 0,
                                    pointHoverRadius: 5,
                                    pointHitRadius: 10,
                                },
                                {
                                    label: "System Requests",
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
                                tooltip: {
                                    mode: 'index',
                                    intersect: false,
                                    padding: 12,
                                    cornerRadius: 8,
                                }
                            },
                            scales: {
                                x: {
                                    ticks: { color: getColor("slate.400", 0.8), font: { size: 10 } },
                                    grid: { display: false },
                                    border: { display: false }
                                },
                                y: {
                                    ticks: { color: getColor("slate.400", 0.8), font: { size: 10 } },
                                    grid: { color: getColor("slate.400", 0.1) },
                                    border: { display: false }
                                }
                            }
                        }
                    });

                    helper.watchCssVariables("html", ["color-primary"], () => {
                        chart.data.datasets[0].borderColor = getColor("primary", 0.8);
                        chart.data.datasets[0].backgroundColor = gradient();
                        chart.update();
                    });
                });
        });
    </script>
@endpush
