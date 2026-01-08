@extends('layouts.app')
@section('title', 'System: Traffic Observatory')

@section('content')
    <div class="col-span-12">
        <div class="flex flex-col gap-y-3 md:h-10 md:flex-row md:items-center">
            <div class="text-base font-medium group-[.mode--light]:text-white flex items-center gap-2">
                <i data-lucide="monitor-dot" class="w-5 h-5 text-primary"></i>
                Traffic Observatory & Signal Analyzer
            </div>
        </div>

        {{-- TOP STATS --}}
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="col-span-6 sm:col-span-3 box p-5 border-none shadow-sm dark:bg-darkmode-600">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center text-primary">
                        <i data-lucide="zap" class="w-5 h-5"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-xl font-bold">{{ number_format($logs->total()) }}</div>
                        <div class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">Total Signal</div>
                    </div>
                </div>
            </div>
            <div class="col-span-6 sm:col-span-3 box p-5 border-none shadow-sm dark:bg-darkmode-600">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-success/10 rounded-xl flex items-center justify-center text-success">
                        <i data-lucide="clock" class="w-5 h-5"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-xl font-bold">{{ number_format($logs->avg('latency_ms'), 0) }}ms</div>
                        <div class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">Avg Latency</div>
                    </div>
                </div>
            </div>
            <div class="col-span-6 sm:col-span-3 box p-5 border-none shadow-sm dark:bg-darkmode-600">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-danger/10 rounded-xl flex items-center justify-center text-danger">
                        <i data-lucide="bug" class="w-5 h-5"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-xl font-bold">{{ $logs->where('response_status', '>=', 400)->count() }}</div>
                        <div class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">Anomalies</div>
                    </div>
                </div>
            </div>
            <div class="col-span-6 sm:col-span-3 box p-5 border-none shadow-sm bg-theme-1 text-white">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="shield" class="w-5 h-5"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-xl font-bold">{{ $logs->unique('ip_address')->count() }}</div>
                        <div class="text-[9px] text-white/70 font-bold uppercase tracking-widest">Unique IPs</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- DYNAMIC LAYOUT AREA --}}
        <div class="grid grid-cols-12 gap-6 mt-8 relative">
            
            {{-- PART 1: COMPLETE TABLE --}}
            <div id="table-container" class="col-span-12 transition-all duration-500 ease-in-out">
                <div class="box box--stacked bg-white dark:bg-darkmode-600">
                    <div class="p-5 border-b border-slate-100 dark:border-darkmode-400 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i data-lucide="activity" class="w-4 h-4 text-slate-400"></i>
                            <span class="text-xs font-bold uppercase tracking-widest text-slate-600">Active Signal Stream</span>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 dark:bg-darkmode-400">
                                <tr>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Method</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Signal Path</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">Latency</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">Status</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Operator</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">IP Vector</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-right">Timestamp</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-darkmode-400">
                                @forelse($logs as $log)
                                    <tr class="signal-row hover:bg-primary/5 dark:hover:bg-darkmode-700/50 transition-all cursor-pointer border-l-4 border-transparent hover:border-primary" 
                                        data-log="{{ json_encode([
                                            'method' => $log->method,
                                            'path' => $log->path,
                                            'query' => $log->query_params,
                                            'payload' => $log->payload,
                                            'headers' => $log->headers,
                                            'ip' => $log->ip_address,
                                            'agent' => $log->user_agent,
                                            'status' => $log->response_status,
                                            'latency' => $log->latency_ms . 'ms',
                                            'operator' => $log->user ? $log->user->username : 'Guest User',
                                            'timestamp' => $log->created_at->format('Y-m-d H:i:s')
                                        ]) }}">
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-0.5 rounded text-[9px] font-black {{ $log->method == 'GET' ? 'bg-primary/10 text-primary' : 'bg-success/10 text-success' }}">
                                                {{ $log->method }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-semibold truncate max-w-[200px]" title="{{ $log->path }}">/{{ $log->path ?: 'root' }}</div>
                                            @if($log->query_params)
                                                <div class="text-[8px] text-primary font-bold uppercase tracking-tighter mt-0.5 flex items-center gap-1">
                                                    <i data-lucide="link" class="w-2 h-2"></i> Query Trace Attached
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center font-mono text-xs">{{ $log->latency_ms }}ms</td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="text-xs font-bold {{ $log->response_status >= 400 ? 'text-danger' : 'text-slate-500' }}">
                                                {{ $log->response_status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-xs font-medium text-slate-600">{{ $log->user ? $log->user->username : '-' }}</td>
                                        <td class="px-6 py-4 font-mono text-[10px] text-slate-400">{{ $log->ip_address }}</td>
                                        <td class="px-6 py-4 text-right text-[10px] text-slate-400 whitespace-nowrap">{{ $log->created_at->format('H:i:s') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="p-20 text-center italic text-slate-300 text-xs uppercase tracking-widest">Listening for signals...</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- PART 2: THE REFINED DETAIL BOX --}}
            <div id="detail-panel" class="hidden transition-all duration-500 ease-in-out">
                <div class="box box--stacked bg-white dark:bg-darkmode-600 shadow-2xl border-2 border-primary/5 rounded-2xl overflow-hidden sticky top-5">
                    
                    {{-- HEADER --}}
                    <div class="p-10 border-b dark:border-darkmode-400 bg-slate-50/50 dark:bg-darkmode-700/50 flex items-center justify-between">
                        <div class="flex items-center gap-5">
                            <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-primary border border-primary/20 shadow-inner">
                                <i data-lucide="microscope" class="w-7 h-7"></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-black text-slate-800 dark:text-slate-200 tracking-tight">Signal Investigation</h4>
                                <p class="text-[10px] text-slate-500 uppercase tracking-widest mt-1 font-bold" id="detail-path-title">Analysis Mode Active</p>
                            </div>
                        </div>
                        <button type="button" id="close-panel" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white dark:bg-darkmode-500 text-slate-400 hover:bg-danger hover:text-white transition-all shadow-sm border border-slate-100 dark:border-darkmode-400">
                            <i data-lucide="x" class="w-6 h-6"></i>
                        </button>
                    </div>

                    {{-- SCROLLABLE CONTENT WITH HUGE PADDING & MARGINS --}}
                    <div class="p-12 max-h-[75vh] overflow-y-auto custom-scrollbar space-y-20">
                        
                        {{-- QUICK META GRID --}}
                        <div id="detail-meta-grid" class="grid grid-cols-2 gap-6"></div>

                        {{-- SECTOR: QUERY --}}
                        <div class="space-y-6">
                            <div class="flex items-center gap-3 border-b-2 border-slate-50 dark:border-darkmode-400 pb-4 mb-6">
                                <i data-lucide="link-2" class="w-5 h-5 text-primary"></i>
                                <span class="text-xs font-black text-slate-800 dark:text-slate-300 uppercase tracking-[0.25em]">01_Inbound_Parameters</span>
                            </div>
                            <div id="detail-query-container" class="grid grid-cols-1 gap-5"></div>
                        </div>

                        {{-- SECTOR: PAYLOAD --}}
                        <div class="space-y-6">
                            <div class="flex items-center gap-3 border-b-2 border-slate-50 dark:border-darkmode-400 pb-4 mb-6">
                                <i data-lucide="box" class="w-5 h-5 text-success"></i>
                                <span class="text-xs font-black text-slate-800 dark:text-slate-300 uppercase tracking-[0.25em]">02_Payload_Transmission</span>
                            </div>
                            <div id="detail-payload-container" class="grid grid-cols-1 gap-5"></div>
                        </div>

                        {{-- SECTOR: HEADERS --}}
                        <div class="space-y-6">
                            <div class="flex items-center gap-3 border-b-2 border-slate-50 dark:border-darkmode-400 pb-4 mb-6">
                                <i data-lucide="list-tree" class="w-5 h-5 text-slate-400"></i>
                                <span class="text-xs font-black text-slate-800 dark:text-slate-300 uppercase tracking-[0.25em]">03_Metadata_Headers</span>
                            </div>
                            <div id="detail-headers-container" class="grid grid-cols-1 gap-5"></div>
                        </div>

                        {{-- SECTOR: AGENT --}}
                        <div class="space-y-6 pb-12">
                             <div class="flex items-center gap-3 border-b-2 border-slate-50 dark:border-darkmode-400 pb-4 mb-6">
                                <i data-lucide="cpu" class="w-5 h-5 text-slate-400"></i>
                                <span class="text-xs font-black text-slate-800 dark:text-slate-300 uppercase tracking-[0.25em]">04_Environment_Agent</span>
                            </div>
                            <div class="p-10 bg-slate-900 rounded-[2rem] shadow-2xl border border-white/5 relative overflow-hidden group/agent">
                                <div class="absolute top-0 right-0 p-4 opacity-10">
                                    <i data-lucide="shield-check" class="w-20 h-20 text-emerald-500"></i>
                                </div>
                                <p id="detail-agent" class="text-xs font-mono text-emerald-400 break-all leading-loose relative z-10 selection:bg-emerald-500/30"></p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.08); border-radius: 10px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.05); }
    </style>
    <script>
        lucide.createIcons();

        const tableContainer = document.getElementById('table-container');
        const detailPanel = document.getElementById('detail-panel');
        const closePanelBtn = document.getElementById('close-panel');

        const containers = {
            query: document.getElementById('detail-query-container'),
            payload: document.getElementById('detail-payload-container'),
            headers: document.getElementById('detail-headers-container'),
            meta: document.getElementById('detail-meta-grid')
        };

        const closePanel = () => {
            detailPanel.classList.add('hidden');
            detailPanel.classList.remove('col-span-12', 'lg:col-span-5');
            tableContainer.classList.remove('lg:col-span-7');
            tableContainer.classList.add('col-span-12');
            document.querySelectorAll('.signal-row').forEach(r => r.classList.remove('bg-primary/10', 'border-primary'));
        };

        closePanelBtn.onclick = closePanel;

        const renderKV = (target, data) => {
            target.innerHTML = '';
            if (!data || Object.keys(data).length === 0) {
                target.innerHTML = `
                    <div class="py-8 text-center bg-slate-50/50 dark:bg-darkmode-700/50 rounded-2xl border-2 border-dashed border-slate-100 dark:border-darkmode-400">
                        <span class="text-xs text-slate-400 font-mono italic lowercase tracking-widest">// signal_data_null //</span>
                    </div>
                `;
                return;
            }

            Object.entries(data).forEach(([key, val]) => {
                const display = typeof val === 'object' ? JSON.stringify(val) : val;
                target.innerHTML += `
                    <div class="bg-white dark:bg-darkmode-700 border border-slate-200 dark:border-darkmode-400 p-6 rounded-[1.5rem] shadow-sm hover:border-primary/40 transition-all group">
                        <div class="text-[9px] text-slate-400 font-black uppercase tracking-[0.2em] mb-3 group-hover:text-primary transition-colors">${key}</div>
                        <div class="text-[11px] font-mono text-slate-700 dark:text-slate-200 break-all leading-relaxed">${display || 'null'}</div>
                    </div>
                `;
            });
        };

        document.querySelectorAll('.signal-row').forEach(row => {
            row.onclick = () => {
                const data = JSON.parse(row.getAttribute('data-log'));
                
                tableContainer.classList.remove('col-span-12');
                tableContainer.classList.add('lg:col-span-7');
                detailPanel.classList.remove('hidden');
                detailPanel.classList.add('col-span-12', 'lg:col-span-5');

                document.querySelectorAll('.signal-row').forEach(r => r.classList.remove('bg-primary/10', 'border-primary'));
                row.classList.add('bg-primary/10', 'border-primary');

                document.getElementById('detail-path-title').textContent = `INBOUND_TRACE: /${data.path || 'ROOT'}`;
                
                const meta = [
                    { k: 'METHOD_ID', v: data.method, c: 'text-primary' },
                    { k: 'SIG_STATUS', v: data.status, c: data.status >= 400 ? 'text-danger' : 'text-success' },
                    { k: 'LATENCY_VAL', v: data.latency, c: 'text-slate-800 dark:text-slate-200' },
                    { k: 'IP_ORIGIN', v: data.ip, c: 'text-slate-500 font-mono text-[10px]' }
                ];
                containers.meta.innerHTML = meta.map(m => `
                    <div class="p-6 bg-slate-50/50 dark:bg-darkmode-800 rounded-2xl border border-slate-200 dark:border-darkmode-400 group">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-2">${m.k}</span>
                        <span class="text-sm font-black uppercase ${m.c}">${m.v}</span>
                    </div>
                `).join('');

                renderKV(containers.query, data.query);
                renderKV(containers.payload, data.payload);
                renderKV(containers.headers, data.headers);
                document.getElementById('detail-agent').textContent = data.agent;

                lucide.createIcons();
                detailPanel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            };
        });
    </script>
@endpush
