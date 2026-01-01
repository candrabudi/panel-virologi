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

            <div class="tab-content box box--stacked mt-3.5">
                <div class="tab-pane active flex flex-col gap-2 p-1.5 leading-relaxed xl:flex-row">
                    <div class="grid w-full grid-cols-4 gap-2">
                        <div
                            class="box relative col-span-4 flex-1 overflow-hidden rounded-[0.6rem] border-0 bg-slate-50 bg-gradient-to-b from-theme-2/90 to-theme-1/[0.85] p-5 sm:col-span-2 xl:col-span-1">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full border border-white/10 bg-white/10">
                                <i data-lucide="database" class="stroke-[1] h-6 w-6 fill-white/10 text-white"></i>
                            </div>
                            <div class="mt-12 flex items-center">
                                <div id="totalThreats" class="text-2xl font-medium text-white">0</div>
                            </div>
                            <div class="mt-1 text-base text-white/70">
                                Total AI Units
                            </div>
                        </div>
                        <div
                            class="relative col-span-4 flex-1 overflow-hidden rounded-[0.6rem] border bg-slate-50/50 p-5 sm:col-span-2 xl:col-span-1">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full border border-primary/10 bg-primary/10">
                                <i data-lucide="activity" class="stroke-[1] h-6 w-6 fill-primary/10 text-primary"></i>
                            </div>
                            <div class="mt-12 flex items-center">
                                <div id="blockedAttacks" class="text-2xl font-medium">0</div>
                            </div>
                            <div class="mt-1 text-base text-slate-500">
                                Total AI Requests
                            </div>
                        </div>
                        <div
                            class="relative col-span-4 flex-1 overflow-hidden rounded-[0.6rem] border bg-slate-50/50 p-5 sm:col-span-2 xl:col-span-1">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full border border-info/10 bg-info/10">
                                <i data-lucide="globe" class="stroke-[1] h-6 w-6 fill-info/10 text-info"></i>
                            </div>
                            <div class="mt-12 flex items-center">
                                <div id="activeIps" class="text-2xl font-medium">0</div>
                            </div>
                            <div class="mt-1 text-base text-slate-500">
                                Active IP Addresses
                            </div>
                        </div>
                        <div
                            class="relative col-span-4 flex-1 overflow-hidden rounded-[0.6rem] border bg-slate-50/50 p-5 sm:col-span-2 xl:col-span-1">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full border border-success/10 bg-success/10">
                                <i data-lucide="check-circle" class="stroke-[1] h-6 w-6 fill-success/10 text-success"></i>
                            </div>
                            <div class="mt-12 flex items-center">
                                <div id="securityScore" class="text-2xl font-medium">0%</div>
                            </div>
                            <div class="mt-1 text-base text-slate-500">
                                Prompt Success Rate
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <div class="box box--stacked mt-3.5 p-5">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="sm:mr-auto">
                        <div class="text-base text-slate-500">
                           AI Traffic Overview (Monthly)
                        </div>
                        <div class="flex items-center mt-1">
                            <div class="text-xl font-medium" id="totaltotal30Days30Days"></div>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="w-auto h-[317px]">
                        <canvas class="chart report-line-chart-2"></canvas>
                    </div>
                </div>
                <div class="flex flex-col items-center gap-3 mt-5 sm:flex-row">
                    <div class="flex flex-wrap items-center justify-center gap-x-5 gap-y-3">
                        <div class="flex items-center text-slate-500">
                            <div class="w-2 h-2 mr-2 border rounded-full border-primary/60 bg-primary/60"></div>
                            Machine Performance
                        </div>
                        <div class="flex items-center text-slate-500">
                            <div class="w-2 h-2 mr-2 border rounded-full border-slate-500/60 bg-slate-500/60"></div>
                            Defect Rate
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('dist/js/vendors/chartjs.js') }}"></script>
    <script src="dist/js/utils/colors.js"></script>
    <script src="dist/js/utils/helper.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        axios.get('/dashboard/summary')
            .then(res => {
                const d = res.data

                document.getElementById('totalThreats').innerText =
                    d.total_units.toLocaleString()
                document.getElementById('blockedAttacks').innerText =
                    d.success_rate + '%'

                document.getElementById('activeIps').innerText =
                    d.active_ips.toLocaleString()

                document.getElementById('securityScore').innerText =
                    d.growth + '%'
            })
            .catch(err => {
                console.error('Gagal memuat ringkasan dashboard security', err)
            })
    </script>


    <script>
        (() => {
            (function() {
                "use strict";

                const charts = $(".report-line-chart-2");
                if (!charts.length) return;

                axios.get('/dashboard/ai-traffic-daily')
                    .then(res => {
                        const {
                            labels,
                            series
                        } = res.data;

                        charts.each(function() {
                            const ctx = $(this)[0].getContext("2d");

                            const gradient = () => {
                                const g = document.createElement("canvas")
                                    .getContext("2d")
                                    ?.createLinearGradient(0, 0, 0, 210);

                                g?.addColorStop(0, getColor("primary", 0.3));
                                g?.addColorStop(
                                    1,
                                    $("html").hasClass("dark") ?
                                    "#28344e00" :
                                    "#ffffff01"
                                );

                                return g;
                            };

                            const chart = new Chart(ctx, {
                                type: "line",
                                data: {
                                    labels: labels,
                                    datasets: [{
                                            label: "Total Tokens",
                                            data: series.tokens,
                                            borderWidth: 1.3,
                                            borderColor: getColor("primary", 0.7),
                                            pointRadius: 0,
                                            tension: 0.3,
                                            backgroundColor: gradient(),
                                            fill: true
                                        },
                                        {
                                            label: "Total Requests",
                                            data: series.requests,
                                            borderWidth: 1.2,
                                            borderColor: getColor("slate.500", 0.5),
                                            pointRadius: 0,
                                            tension: 0.3,
                                            borderDash: [3, 2],
                                            backgroundColor: "transparent",
                                            fill: false
                                        }
                                    ]
                                },
                                options: {
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            display: false
                                        }
                                    },
                                    scales: {
                                        x: {
                                            ticks: {
                                                autoSkipPadding: 15,
                                                color: getColor("slate.400", 0.8)
                                            },
                                            grid: {
                                                display: false
                                            },
                                            border: {
                                                display: false
                                            }
                                        },
                                        y: {
                                            ticks: {
                                                autoSkipPadding: 20,
                                                color: getColor("slate.400", 0.8)
                                            },
                                            grid: {
                                                color: getColor("slate.400", 0.1)
                                            },
                                            border: {
                                                display: false
                                            }
                                        }
                                    }
                                }
                            });

                            helper.watchCssVariables(
                                "html",
                                ["color-primary"],
                                () => {
                                    chart.data.datasets[0].borderColor = getColor("primary", 0.7);
                                    chart.data.datasets[0].backgroundColor = gradient();
                                    chart.update();
                                }
                            );
                        });
                    })
                    .catch(err => {
                        console.error("Gagal load AI traffic chart:", err);
                    });

            })();
        })();
    </script>
@endpush
