@extends('layouts.app')
@section('title', 'Intelligence Audit: ' . $log->query)

@section('content')
    <div class="col-span-12">
        <div class="flex flex-col gap-y-3 md:h-10 md:flex-row md:items-center">
            <div class="text-base font-medium group-[.mode--light]:text-white">Intelligence Detail</div>
            <div class="flex flex-col gap-x-3 gap-y-2 sm:flex-row md:ml-auto">
                <a href="{{ route('leak_check.download_json', $log->id) }}" class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed bg-primary border-primary text-white dark:border-primary group-[.mode--light]:!border-transparent group-[.mode--light]:!bg-white/[0.12] group-[.mode--light]:!text-slate-200">
                    <i data-lucide="download" class="stroke-[1.3] w-4 h-4 mr-2"></i> Download JSON
                </a>
            </div>
        </div>
        
        <div class="mt-3.5 flex flex-col gap-8">
            <div class="box box--stacked flex flex-col">
                <div class="flex flex-col gap-y-2 p-5 sm:flex-row sm:items-center">
                    <div>
                        <div class="relative">
                            <i data-lucide="search" class="absolute inset-y-0 left-0 z-10 my-auto ml-3 h-4 w-4 stroke-[1.3] text-slate-500"></i>
                            <input type="text" placeholder="Search data..." class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 rounded-[0.5rem] pl-9 sm:w-64">
                        </div>
                    </div>
                </div>
                
                <div class="overflow-auto xl:overflow-visible">
                    <table class="w-full text-left border-b border-slate-200/60">
                        <thead>
                            <tr>
                                <td class="px-5 border-b dark:border-darkmode-300 border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500">
                                    Source & Info
                                </td>
                                <td class="px-5 border-b dark:border-darkmode-300 border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500">
                                    Identity Profile
                                </td>
                                <td class="px-5 border-b dark:border-darkmode-300 border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500 text-center">
                                    Status
                                </td>
                                <td class="px-5 border-b dark:border-darkmode-300 border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500">
                                    Metadata
                                </td>
                                <td class="px-5 border-b dark:border-darkmode-300 border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500 text-center">
                                    Action
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $item)
                                <tr class="[&_td]:last:border-b-0">
                                    <td class="px-5 border-b border-dashed py-4 dark:bg-darkmode-600">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 image-fit zoom-in -ml-5 first:ml-0">
                                                <div class="rounded-full bg-slate-100 dark:bg-darkmode-400 flex items-center justify-center w-full h-full text-slate-500">
                                                    <i data-lucide="database" class="w-5 h-5 stroke-[1.3]"></i>
                                                </div>
                                            </div>
                                            <div class="ml-3.5">
                                                <div class="whitespace-nowrap font-medium text-slate-700 dark:text-slate-200">
                                                    {{ $item['source_name'] }}
                                                </div>
                                                <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">
                                                    {{ Str::limit($item['source_info'], 30) }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-5 border-b border-dashed py-4 dark:bg-darkmode-600">
                                        <div class="whitespace-nowrap font-medium text-slate-700 dark:text-slate-200">
                                            {{ $item['identity'] }}
                                        </div>
                                        <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">
                                            Primary Identity
                                        </div>
                                    </td>
                                    
                                    <td class="px-5 border-b border-dashed py-4 dark:bg-darkmode-600 text-center">
                                        @if($item['password'])
                                            <div class="flex items-center justify-center text-danger">
                                                <i data-lucide="alert-circle" class="w-4 h-4 mr-2 stroke-[1.3]"></i> Leaked
                                            </div>
                                        @else
                                            <div class="flex items-center justify-center text-success">
                                                <i data-lucide="check-square" class="w-4 h-4 mr-2 stroke-[1.3]"></i> Safe
                                            </div>
                                        @endif
                                    </td>
                                    
                                    <td class="px-5 border-b border-dashed py-4 dark:bg-darkmode-600">
                                        <div class="flex flex-wrap gap-2 max-w-[300px]">
                                            @if(count($item['other_data']) > 0)
                                                @foreach(array_slice($item['other_data'], 0, 3) as $k => $v)
                                                    @if(!empty($v) && $v !== '0')
                                                    <span class="text-xs px-2 py-0.5 rounded-full bg-slate-100 dark:bg-darkmode-400 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-darkmode-300">
                                                        {{ $k }}: {{ Str::limit($v, 15) }}
                                                    </span>
                                                    @endif
                                                @endforeach
                                                @if(count($item['other_data']) > 3)
                                                    <span class="text-xs px-2 py-0.5 text-slate-400">+{{ count($item['other_data']) - 3 }} more</span>
                                                @endif
                                            @else
                                                <span class="text-slate-400 text-xs">-</span>
                                            @endif
                                        </div>
                                    </td>
                                    
                                    <td class="px-5 border-b border-dashed py-4 dark:bg-darkmode-600 relative text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button type="button" class="view-detail-btn transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed border-secondary text-slate-500 dark:border-darkmode-100/40 dark:text-slate-300 [&:hover:not(:disabled)]:bg-secondary/20 [&:hover:not(:disabled)]:dark:bg-darkmode-100/10"
                                                    data-tw-toggle="modal" 
                                                    data-tw-target="#detail-modal"
                                                    data-source="{{ $item['source_name'] }}"
                                                    data-info="{{ $item['source_info'] }}"
                                                    data-identity="{{ $item['identity'] }}"
                                                    data-password="{{ $item['password'] ?? 'NO_PASSWORD' }}"
                                                    data-meta="{{ json_encode($item['other_data']) }}"
                                                    title="View Details">
                                                <i data-lucide="scan-eye" class="w-4 h-4 stroke-[1.3]"></i>
                                            </button>

                                            @if($item['password'])
                                                <button class="password-toggle transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed border-secondary text-slate-500 dark:border-darkmode-100/40 dark:text-slate-300 [&:hover:not(:disabled)]:bg-secondary/20 [&:hover:not(:disabled)]:dark:bg-darkmode-100/10" title="Reveal Password">
                                                    <i data-lucide="eye" class="w-4 h-4 stroke-[1.3]"></i>
                                                    <span class="hidden password-value ml-2" data-value="{{ $item['password'] }}"></span>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-4 text-center text-slate-500">
                                        No data found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="flex flex-col-reverse flex-wrap items-center gap-y-2 p-5 sm:flex-row border-t border-slate-200/60 dark:border-darkmode-400">
                    <div class="mr-auto text-slate-500 text-xs">
                        Showing {{ $items->firstItem() ?? 0 }} to {{ $items->lastItem() ?? 0 }} of {{ $items->total() }} entries
                    </div>
                    <div>
                        {{ $items->links('pagination::simple-tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- VIEW DETAIL MODAL --}}
    <div id="detail-modal" tabindex="-1" aria-hidden="true" class="modal group bg-gradient-to-b from-theme-1/50 via-theme-2/50 to-black/50 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 overflow-y-hidden z-[60] [&:not(.show)]:duration-[0s,0.2s] [&:not(.show)]:delay-[0.2s,0s] [&:not(.show)]:invisible [&:not(.show)]:opacity-0 [&.show]:visible [&.show]:opacity-100 [&.show]:duration-[0s,0.1s] overflow-y-auto">
        <div class="relative mx-auto my w-[95%] scale-95 transition-transform group-[.show]:scale-100 sm:mt-40 sm:w-[600px] lg:w-[700px]">
            <div class="modal-content bg-white dark:bg-darkmode-600 rounded-lg shadow-xl overflow-hidden relative">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200/60 dark:border-darkmode-400">
                    <h3 class="text-base font-semibold text-slate-800 dark:text-slate-200">
                        Intelligence Record Details
                    </h3>
                    <button data-tw-dismiss="modal" class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                
                <div class="p-5 grid grid-cols-12 gap-6">
                    {{-- Source Info --}}
                    <div class="col-span-12 p-4 bg-slate-50 dark:bg-darkmode-700/50 rounded-lg">
                        <div class="flex items-center gap-3 mb-2">
                             <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                                <i data-lucide="database" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <div id="modal-source" class="text-sm font-bold text-slate-800 dark:text-slate-200">SOURCE_NAME</div>
                                <div id="modal-info" class="text-xs text-slate-500">Source Information Description</div>
                            </div>
                        </div>
                    </div>

                    {{-- Identity & Password --}}
                    <div class="col-span-12 md:col-span-6">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 block">Primary Identity</label>
                        <div class="flex items-center gap-2 p-3 border border-slate-200 dark:border-darkmode-400 rounded-lg">
                            <i data-lucide="user" class="w-4 h-4 text-slate-400"></i>
                            <span id="modal-identity" class="text-sm font-mono font-semibold text-slate-700 dark:text-slate-300 break-all">identity@example.com</span>
                        </div>
                    </div>
                     <div class="col-span-12 md:col-span-6">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 block">Compromised Secret</label>
                        <div class="flex items-center gap-2 p-3 border border-slate-200 dark:border-darkmode-400 rounded-lg bg-red-50 dark:bg-red-500/10 border-red-200 dark:border-red-500/20">
                            <i data-lucide="key" class="w-4 h-4 text-red-500"></i>
                            <span id="modal-password" class="text-sm font-mono font-bold text-red-600 dark:text-red-400 break-all">PASSWORD123</span>
                        </div>
                    </div>

                    {{-- Full Metadata --}}
                    <div class="col-span-12">
                         <div class="flex items-center justify-between mb-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Extended Metadata</label>
                            <span class="text-[10px] bg-slate-100 dark:bg-darkmode-400 px-2 py-0.5 rounded text-slate-500 font-mono">ALL FIELDS</span>
                        </div>
                        <div id="modal-metadata-container" class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-[300px] overflow-y-auto p-1 custom-scrollbar">
                            <!-- Dynamic Content -->
                        </div>
                    </div>
                </div>
                
                <div class="px-5 py-4 border-t border-slate-200/60 dark:border-darkmode-400 flex justify-end">
                    <button data-tw-dismiss="modal" class="btn border-slate-300 dark:border-darkmode-400 text-slate-500 w-24 mr-1">Close</button>
                    <!-- <button class="btn btn-primary w-24">Copy</button> -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
        
        // Password Toggle Logic
        document.querySelectorAll('.password-toggle').forEach(btn => {
            btn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                const span = this.querySelector('.password-value');
                const password = span.getAttribute('data-value');
                
                if (span.classList.contains('hidden')) {
                    // Reveal
                    span.textContent = password;
                    span.classList.remove('hidden');
                    icon.classList.add('hidden');
                    this.classList.add('w-auto');
                } else {
                    // Hide
                    span.textContent = '';
                    span.classList.add('hidden');
                    icon.classList.remove('hidden');
                    this.classList.remove('w-auto');
                }
            });
        });

        // Detail Modal Logic (Vanilla JS)
        document.querySelectorAll('.view-detail-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const source = this.dataset.source;
                const info = this.dataset.info;
                const identity = this.dataset.identity;
                const password = this.dataset.password;
                const meta = JSON.parse(this.dataset.meta);

                // Populate Modal
                document.getElementById('modal-source').textContent = source;
                document.getElementById('modal-info').textContent = info;
                document.getElementById('modal-identity').textContent = identity;
                document.getElementById('modal-password').textContent = password;

                const metaContainer = document.getElementById('modal-metadata-container');
                metaContainer.innerHTML = ''; // Clear previous

                if (Object.keys(meta).length > 0) {
                    for (const [key, value] of Object.entries(meta)) {
                        if(value && value !== '0') {
                            const item = document.createElement('div');
                            item.className = 'flex flex-col p-2.5 rounded border border-slate-100 dark:border-darkmode-400 bg-slate-50/50 dark:bg-darkmode-700/30';
                            item.innerHTML = `
                                <span class="text-[9px] uppercase font-bold text-slate-400 tracking-wider mb-1">${key}</span>
                                <span class="text-xs font-medium text-slate-700 dark:text-slate-300 break-all">${value}</span>
                            `;
                            metaContainer.appendChild(item);
                        }
                    }
                } else {
                    metaContainer.innerHTML = '<div class="col-span-2 text-center text-slate-400 text-xs py-4 italic">No extended metadata available for this record.</div>';
                }
            });
        });
    </script>
@endpush
