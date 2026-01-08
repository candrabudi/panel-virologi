@extends('layouts.app')
@section('title', 'Leak Intelligence: Setup & Diagnostics')
@section('content')
    <div class="col-span-12">
        <div class="flex flex-col gap-y-3 md:h-10 md:flex-row md:items-center">
            <div class="text-base font-medium group-[.mode--light]:text-white flex items-center gap-2">
                <i data-lucide="radar" class="w-5 h-5 text-primary"></i>
                OSINT Intelligence Matrix
            </div>
            <div class="flex flex-col gap-x-3 gap-y-2 md:ml-auto md:flex-row">
                <button type="submit" form="leak-settings-form" class="transition-all active:scale-95 inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-primary rounded-lg shadow-lg shadow-primary/20 hover:bg-primary/90">
                    <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                    Save Configuration
                </button>
            </div>
        </div>

        <div class="mt-5 grid grid-cols-12 gap-6">
            {{-- LEFT: CONFIGURATION --}}
            <div class="col-span-12 lg:col-span-5 space-y-6">
                <div class="box box--stacked p-7">
                    <div class="flex items-center pb-5 mb-5 border-b border-slate-100 dark:border-darkmode-400">
                        <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center text-primary">
                            <i data-lucide="shield-check" class="w-5 h-5"></i>
                        </div>
                        <div class="ml-4">
                            <div class="text-base font-bold text-slate-700 dark:text-slate-200">API Authentication</div>
                            <div class="text-xs text-slate-500 mt-0.5 uppercase tracking-wider font-semibold">Security Credentials</div>
                        </div>
                    </div>

                    <form id="leak-settings-form" class="space-y-5">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Gateway Endpoint</label>
                            <div class="relative">
                                <i data-lucide="link" class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="text" name="api_endpoint" value="{{ $setting->api_endpoint ?? 'https://leakosintapi.com/' }}" 
                                    class="w-full rounded-lg border-slate-200 dark:border-darkmode-400 bg-slate-50/50 dark:bg-darkmode-700 py-3 pl-11 pr-4 text-sm font-medium focus:ring-primary/20 focus:border-primary transition-all shadow-sm"
                                    placeholder="https://api.example.com">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Access Token</label>
                            <div class="relative">
                                <i data-lucide="key" class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="password" name="api_token" value="{{ $setting->api_token ?? '' }}" 
                                    class="w-full rounded-lg border-slate-200 dark:border-darkmode-400 bg-slate-50/50 dark:bg-darkmode-700 py-3 pl-11 pr-12 text-sm font-medium focus:ring-primary/20 focus:border-primary transition-all shadow-sm"
                                    placeholder="Enter Token...">
                                <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary transition-colors toggle-password">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Hard Limit</label>
                                <input type="number" name="default_limit" value="{{ $setting->default_limit ?? 100 }}" 
                                    class="w-full rounded-lg border-slate-200 dark:border-darkmode-400 bg-slate-50/50 dark:bg-darkmode-700 py-3 px-4 text-sm font-bold shadow-sm"
                                    min="100" max="10000">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Locale</label>
                                <input type="text" name="lang" value="{{ $setting->lang ?? 'en' }}" 
                                    class="w-full rounded-lg border-slate-200 dark:border-darkmode-400 bg-slate-50/50 dark:bg-darkmode-700 py-3 px-4 text-sm font-bold shadow-sm"
                                    placeholder="en, ru, etc.">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Bot Identifier</label>
                            <input type="text" name="bot_name" value="{{ $setting->bot_name ?? '' }}" 
                                class="w-full rounded-lg border-slate-200 dark:border-darkmode-400 bg-slate-50/50 dark:bg-darkmode-700 py-3 px-4 text-sm font-medium shadow-sm"
                                placeholder="@bot_name (optional)">
                        </div>

                        <div class="pt-4 mt-2 border-t border-slate-100 dark:border-darkmode-400">
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" name="is_enabled" value="1" {{ ($setting->is_enabled ?? true) ? 'checked' : '' }} class="hidden peer">
                                <div class="w-10 h-6 bg-slate-200 peer-checked:bg-primary rounded-full relative transition-all duration-300 mr-3 after:content-[''] after:absolute after:w-4 after:h-4 after:bg-white after:rounded-full after:top-1 after:left-1 peer-checked:after:left-5 after:transition-all after:duration-300"></div>
                                <span class="text-sm font-semibold text-slate-500 group-hover:text-slate-800 dark:group-hover:text-slate-200 transition-colors">Operational Status</span>
                            </label>
                        </div>
                    </form>
                </div>
            </div>

            {{-- RIGHT: INTELLIGENCE TERMINAL --}}
            <div class="col-span-12 lg:col-span-7">
                <div class="box box--stacked p-0 overflow-hidden bg-[#0a0c10] border-none shadow-2xl h-full flex flex-col">
                    <div class="flex items-center gap-2 px-6 py-4 bg-slate-900/50 border-b border-white/5">
                        <div class="flex gap-1.5 mr-4">
                            <div class="w-3 h-3 rounded-full bg-rose-500/50"></div>
                            <div class="w-3 h-3 rounded-full bg-amber-500/50"></div>
                            <div class="w-3 h-3 rounded-full bg-emerald-500/50"></div>
                        </div>
                        <div class="text-[10px] font-mono text-slate-500 uppercase tracking-widest">Diagnostic Terminal v2.4</div>
                    </div>

                    <div class="p-8 flex-1 flex flex-col min-h-[500px]">
                        <div class="flex gap-3 mb-8">
                            <div class="relative flex-1">
                                <input type="text" id="test-query" class="w-full rounded-lg bg-white/5 border-white/10 py-3.5 px-5 text-emerald-500 font-mono text-sm focus:ring-emerald-500/20 focus:border-emerald-500/50 placeholder:text-slate-600 transition-all" placeholder="Enter diagnostic signature (email, username, phone)...">
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 bg-emerald-500/10 text-emerald-500 text-[9px] px-2 py-0.5 rounded border border-emerald-500/20 font-bold uppercase tracking-wider">Input Ready</div>
                            </div>
                            <button type="button" onclick="testSearch()" id="btn-test-search" class="px-6 py-3.5 bg-emerald-500 text-slate-900 rounded-lg font-bold text-sm hover:bg-emerald-400 transition-all flex items-center gap-2">
                                <i data-lucide="zap" class="w-4 h-4 fill-current"></i>
                                Execute
                            </button>
                        </div>

                        <div id="test-result" class="hidden flex-1 overflow-auto rounded-lg bg-black/40 border border-white/5 p-6 custom-scrollbar">
                            <div id="result-items" class="space-y-8">
                                {{-- Rendered Intelligence Items --}}
                            </div>
                        </div>

                        <div id="test-empty" class="flex-1 flex flex-col items-center justify-center py-20 bg-white/[0.02] rounded-lg border border-dashed border-white/5">
                            <div class="relative">
                                <i data-lucide="scan" class="w-16 h-16 text-slate-800 animate-pulse"></i>
                            </div>
                            <p class="mt-6 text-xs font-bold text-slate-600 uppercase tracking-[0.3em]">Standby for Operational Query</p>
                        </div>
                    </div>

                    <div class="px-8 py-3 bg-slate-900/30 border-t border-white/5 flex items-center justify-between">
                        <div id="status-line" class="text-[10px] font-mono text-slate-500 truncate">System: Authorized // Waiting for prompt...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.05); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.1); }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        lucide.createIcons();

        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Toggle Password Visibility
        document.querySelectorAll('.toggle-password').forEach(btn => {
             btn.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input');
                input.type = input.type === 'password' ? 'text' : 'password';
                lucide.createIcons();
            });
        });

        const form = document.getElementById('leak-settings-form');
        const submitBtn = document.querySelector('button[form="leak-settings-form"]');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const originalBtnHtml = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i data-lucide="refresh-cw" class="w-4 h-4 mr-2 animate-spin"></i> SYNCING...';
            lucide.createIcons();

            const formData = new FormData(form);
            if (!formData.has('is_enabled')) formData.append('is_enabled', '0');

            try {
                await axios.post('{{ route('leak_check.update_settings') }}', formData);
                if (typeof showToast === 'function') showToast('success', 'Update Node', 'Parameters synchronized.');
            } catch (err) {
                if (typeof showToast === 'function') showToast('error', 'Sync Failure', 'Failed to synchronize.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
                lucide.createIcons();
            }
        });

        async function testSearch() {
            const query = document.getElementById('test-query').value;
            if (!query) return;

            const btn = document.getElementById('btn-test-search');
            const resultBox = document.getElementById('test-result');
            const emptyBox = document.getElementById('test-empty');
            const resultItems = document.getElementById('result-items');
            const statusLine = document.getElementById('status-line');

            btn.disabled = true;
            statusLine.textContent = `System: Scanning [${query}]...`;
            statusLine.className = 'text-[10px] font-mono text-amber-500 truncate';

            try {
                const res = await axios.post('{{ route('leak_check.search') }}', { query });
                const data = res.data.data;
                
                emptyBox.classList.add('hidden');
                resultBox.classList.remove('hidden');
                resultItems.innerHTML = '';

                if (!data || !data.List || Object.keys(data.List).length === 0) {
                    resultItems.innerHTML = '<div class="text-slate-500 font-mono text-xs uppercase tracking-widest text-center py-20">// No Breach Found In Public Sectors</div>';
                } else {
                    Object.keys(data.List).forEach(dbName => {
                        const db = data.List[dbName];
                        const section = document.createElement('div');
                        section.className = 'space-y-4';
                        
                        let rowsHtml = '';
                        if (db.Data && db.Data.length > 0) {
                            db.Data.forEach(item => {
                                let fields = '';
                                Object.keys(item).forEach(key => {
                                    fields += `
                                        <div class="flex border-b border-white/[0.02] py-1 last:border-0 hover:bg-white/[0.02] transition-colors rounded">
                                            <span class="text-slate-600 w-20 shrink-0 font-mono text-[9px] uppercase pt-0.5">${key}</span>
                                            <span class="${key.toLowerCase() === 'password' ? 'text-emerald-400 font-bold' : 'text-slate-300'} font-mono text-[11px] break-all">${item[key]}</span>
                                        </div>
                                    `;
                                });
                                rowsHtml += `<div class="bg-white/[0.01] border border-white/[0.03] rounded p-3 mb-2">${fields}</div>`;
                            });
                        }

                        section.innerHTML = `
                            <div class="flex items-center gap-2 border-l-2 border-emerald-500 pl-3">
                                <span class="text-[11px] font-black text-white uppercase tracking-wider">${dbName}</span>
                                <span class="text-[9px] px-1.5 py-0.5 bg-emerald-500/10 text-emerald-500 rounded font-mono">${db.NumOfResults || 0} hits</span>
                            </div>
                            <div class="grid grid-cols-1 gap-2 pl-3">
                                ${rowsHtml || '<div class="text-[9px] text-slate-700 font-mono">// Access Denied Or Data Refined</div>'}
                            </div>
                        `;
                        resultItems.appendChild(section);
                    });
                }

                statusLine.textContent = `System: Ingested ${data.NumOfResults || 0} leak records for ${query}.`;
                statusLine.className = 'text-[10px] font-mono text-emerald-500 truncate';
            } catch (err) {
                statusLine.textContent = `System Error: ${err.response?.data?.message || 'Access Terminated'}`;
                statusLine.className = 'text-[10px] font-mono text-rose-500 truncate';
            } finally {
                btn.disabled = false;
                lucide.createIcons();
            }
        }
    </script>
@endpush
