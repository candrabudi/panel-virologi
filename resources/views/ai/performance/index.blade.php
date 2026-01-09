@extends('layouts.app')
@section('title', 'AI Performance Metrics')

@section('content')
    <div class="col-span-12">
        <div class="flex flex-col gap-y-3 md:h-10 md:flex-row md:items-center">
            <div class="text-base font-medium group-[.mode--light]:text-white flex items-center gap-2">
                <i data-lucide="bar-chart-2" class="w-5 h-5 text-primary"></i>
                AI Performance Intelligence
            </div>
        </div>

        {{-- TOP STATS --}}
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="col-span-6 sm:col-span-3 box p-5 border-none shadow-sm dark:bg-darkmode-600">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center text-primary">
                        <i data-lucide="activity" class="w-5 h-5"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-xl font-bold">{{ number_format($totalQueries) }}</div>
                        <div class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">Total Queries</div>
                    </div>
                </div>
            </div>
            <div class="col-span-6 sm:col-span-3 box p-5 border-none shadow-sm dark:bg-darkmode-600">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-success/10 rounded-xl flex items-center justify-center text-success">
                        <i data-lucide="clock" class="w-5 h-5"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-xl font-bold">{{ number_format($avgResponseTime, 2) }}ms</div>
                        <div class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">Avg Response</div>
                    </div>
                </div>
            </div>
            <div class="col-span-6 sm:col-span-3 box p-5 border-none shadow-sm dark:bg-darkmode-600">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-warning/10 rounded-xl flex items-center justify-center text-warning">
                        <i data-lucide="smile" class="w-5 h-5"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-xl font-bold">{{ number_format($avgSatisfaction, 1) }} / 5.0</div>
                        <div class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">Satisfaction</div>
                    </div>
                </div>
            </div>
            <div class="col-span-6 sm:col-span-3 box p-5 border-none shadow-sm bg-theme-1 text-white">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="book-open" class="w-5 h-5"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-xl font-bold">{{ number_format($totalKnowledgeHits) }}</div>
                        <div class="text-[9px] text-white/70 font-bold uppercase tracking-widest">Knowledge Hits</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MAIN TABLE AREA --}}
        <div class="grid grid-cols-12 gap-6 mt-8 relative">
            
            <div id="table-container" class="col-span-12 transition-all duration-500 ease-in-out">
                <div class="box box--stacked bg-white dark:bg-darkmode-600">
                    <div class="p-5 border-b border-slate-100 dark:border-darkmode-400 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i data-lucide="cpu" class="w-4 h-4 text-slate-400"></i>
                            <span class="text-xs font-bold uppercase tracking-widest text-slate-600">Daily Performance Stream</span>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 dark:bg-darkmode-400">
                                <tr>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Date</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">Queries</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">Response Time</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">Success / Fail</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">Satisfaction</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-darkmode-400">
                                @forelse($metrics as $metric)
                                    <tr class="hover:bg-primary/5 dark:hover:bg-darkmode-700/50 transition-all border-l-4 border-transparent hover:border-primary group">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 rounded bg-primary/10 flex items-center justify-center text-primary mr-3">
                                                    <i data-lucide="calendar" class="w-4 h-4"></i>
                                                </div>
                                                <span class="text-xs font-bold font-mono text-slate-700 dark:text-slate-300">
                                                    {{ $metric->metric_date }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-2 py-1 rounded text-xs font-bold bg-slate-100 dark:bg-darkmode-700">
                                                {{ number_format($metric->total_queries) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="text-xs font-mono {{ $metric->average_response_time > 2000 ? 'text-danger' : 'text-success' }}">
                                                {{ number_format($metric->average_response_time, 2) }}ms
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2 text-xs">
                                                <span class="text-success font-bold">{{ $metric->successful_responses }}</span>
                                                <span class="text-slate-300">/</span>
                                                <span class="text-danger font-bold">{{ $metric->failed_responses }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-1">
                                                <i data-lucide="star" class="w-3 h-3 {{ $metric->user_satisfaction_score >= 4 ? 'text-warning fill-warning' : 'text-slate-300' }}"></i>
                                                <span class="text-xs font-bold">{{ $metric->user_satisfaction_score }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-20 text-center italic text-slate-300 text-xs uppercase tracking-widest">
                                            <div class="mb-3"><i data-lucide="bar-chart-2" class="w-10 h-10 mx-auto text-slate-200"></i></div>
                                            No performance data captured yet
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="flex flex-wrap items-center col-span-12 intro-y sm:flex-row sm:flex-nowrap mt-3">
            {{ $metrics->links() }}
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
@endpush
