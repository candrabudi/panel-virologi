@extends('template.app')
@section('title', 'Dashboard')
@section('content')
    <div class="row">
        <div class="col-xxl-5 mt-2">
            <div class="row g-2">

                <div class="col-xl-6">
                    <div class="card m-0">
                        <div class="card-header border-0">
                            <h4 class="card-title">Total AI Requests</h4>
                        </div>
                        <div class="card-body pt-0">
                            <div class="d-flex align-items-center justify-content-center gap-2 mb-2 py-1">
                                <div class="avatar-md flex-shrink-0">
                                    <span class="avatar-title text-bg-primary rounded-circle">
                                        <i class="ri-cpu-line fs-xxl"></i>
                                    </span>
                                </div>
                                <h3 class="mb-0 fw-bold">
                                    <span id="totalOrders">0</span>
                                </h3>
                            </div>
                            <p class="mb-0 text-muted">
                                <span class="text-nowrap">Total prompt processed</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card m-0">
                        <div class="card-header border-0">
                            <h4 class="card-title">Total Token Usage</h4>
                        </div>
                        <div class="card-body pt-0">
                            <div class="d-flex align-items-center justify-content-center gap-2 mb-2 py-1">
                                <div class="avatar-md flex-shrink-0">
                                    <span class="avatar-title text-bg-success rounded-circle">
                                        <i class="ri-flashlight-line fs-xxl"></i>
                                    </span>
                                </div>
                                <h3 class="mb-0 fw-bold">
                                    <span id="totalRevenue">0</span>
                                </h3>
                            </div>
                            <p class="mb-0 text-muted">
                                <span class="text-nowrap">Total tokens consumed</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card m-0">
                        <div class="card-header border-0">
                            <h4 class="card-title">Active Users (IP)</h4>
                        </div>
                        <div class="card-body pt-0">
                            <div class="d-flex align-items-center justify-content-center gap-2 mb-2 py-1">
                                <div class="avatar-md flex-shrink-0">
                                    <span class="avatar-title text-bg-warning rounded-circle">
                                        <i class="ri-user-location-line fs-xxl"></i>
                                    </span>
                                </div>
                                <h3 class="mb-0 fw-bold">
                                    <span id="activeCustomers">0</span>
                                </h3>
                            </div>
                            <p class="mb-0 text-muted">
                                <span class="text-nowrap">Unique IP access</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card m-0">
                        <div class="card-header border-0">
                            <h4 class="card-title">Prompt Success Rate</h4>
                        </div>
                        <div class="card-body pt-0">
                            <div class="d-flex align-items-center justify-content-center gap-2 mb-2 py-1">
                                <div class="avatar-md flex-shrink-0">
                                    <span class="avatar-title text-bg-info rounded-circle">
                                        <i class="ri-shield-check-line fs-xxl"></i>
                                    </span>
                                </div>
                                <h3 class="mb-0 fw-bold">
                                    <span id="conversionRate">0</span>%
                                </h3>
                            </div>
                            <p class="mb-0 text-muted">
                                <span class="text-nowrap">Passed AI rules</span>
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-xxl-7 mt-2">
            <div class="card h-100 mb-0">
                <div class="card-header border-0 justify-content-between d-flex align-items-center">
                    <h4 class="card-title mb-0">AI Traffic Overview (Daily)</h4>

                    <div class="d-flex gap-2">
                        <select id="trafficDays" class="form-select form-select-sm">
                            <option value="7">Last 7 Days</option>
                            <option value="14">Last 14 Days</option>
                            <option value="30" selected>Last 30 Days</option>
                        </select>
                        <button class="btn btn-sm btn-default" id="reloadTraffic">
                            Refresh
                        </button>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <div dir="ltr">
                        <div id="revenue-chart" class="apex-charts"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>

    <!-- Vector Map Js -->
    <script src="{{ asset('assets/plugins/jsvectormap/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('assets/js/maps/us-aea-en.js') }}"></script>
    <script src="{{ asset('assets/js/maps/us-lcc-en.js') }}"></script>
    <script src="{{ asset('assets/js/maps/us-mill-en.js') }}"></script>
    <script>
        axios.get('/dashboard/summary')
            .then(res => {
                const d = res.data

                document.getElementById('totalOrders').innerText = d.total_orders
                document.getElementById('totalRevenue').innerText = d.total_revenue
                document.getElementById('activeCustomers').innerText = d.active_customers
                document.getElementById('conversionRate').innerText = d.conversion_rate
            })

        axios.get('/dashboard/ai-traffic-daily?days=30')
            .then(res => {
                const d = res.data

                new CustomApexChart({
                    selector: "#revenue-chart",
                    options: () => ({
                        series: [{
                                name: "AI Requests",
                                type: "bar",
                                data: d.series.requests
                            },
                            {
                                name: "Active IPs",
                                type: "bar",
                                data: d.series.active_ips
                            },
                            {
                                name: "Prompt Success Rate",
                                type: "area",
                                data: d.series.success_rate
                            },
                            {
                                name: "Token Usage",
                                type: "line",
                                data: d.series.tokens
                            }
                        ],

                        chart: {
                            height: 251,
                            type: "line",
                            toolbar: {
                                show: false
                            }
                        },

                        stroke: {
                            dashArray: [0, 0, 0, 8],
                            width: [0, 0, 2, 2],
                            curve: "smooth"
                        },

                        fill: {
                            opacity: [1, 1, 0.15, 1],
                            type: ["solid", "solid", "gradient", "solid"],
                            gradient: {
                                type: "vertical",
                                inverseColors: false,
                                opacityFrom: 0.5,
                                opacityTo: 0,
                                stops: [0, 70]
                            }
                        },

                        markers: {
                            size: [0, 0, 0, 0],
                            strokeWidth: 2,
                            hover: {
                                size: 4
                            }
                        },

                        xaxis: {
                            categories: d.labels,
                            axisTicks: {
                                show: false
                            },
                            axisBorder: {
                                show: false
                            }
                        },

                        grid: {
                            show: true,
                            xaxis: {
                                lines: {
                                    show: false
                                }
                            },
                            yaxis: {
                                lines: {
                                    show: true
                                }
                            },
                            padding: {
                                top: -15,
                                right: -15,
                                bottom: 0,
                                left: -15
                            }
                        },

                        legend: {
                            offsetY: 10
                        },

                        plotOptions: {
                            bar: {
                                columnWidth: "45%",
                                borderRadius: 3
                            }
                        },

                        colors: [
                            theme("chart-primary"),
                            theme("chart-secondary"),
                            theme("chart-alpha"),
                            theme("chart-gamma")
                        ],

                        tooltip: {
                            shared: true,
                            y: [{
                                    formatter: v => v + " req"
                                },
                                {
                                    formatter: v => v + " IP"
                                },
                                {
                                    formatter: v => v + "%"
                                },
                                {
                                    formatter: v => v.toLocaleString() + " tokens"
                                }
                            ]
                        }
                    })
                })
            })
    </script>
@endpush
