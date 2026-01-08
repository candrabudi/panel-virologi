@extends('layouts.app')
@section('title', 'Request Details')

@section('content')
    <div class="col-span-12">
        <div class="intro-y flex items-center mt-8">
            <h2 class="text-lg font-medium mr-auto">
                Request Profile
            </h2>
            <div class="w-full sm:w-auto flex mt-4 sm:mt-0 gap-2">
                 <a href="{{ route('leak_request.index') }}" class="btn btn-secondary shadow-md">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back
                </a>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-6 mt-5">
            <!-- BEGIN: Profile Menu -->
            <div class="col-span-12 lg:col-span-4 2xl:col-span-3 flex lg:block flex-col-reverse">
                <div class="intro-y box mt-5 lg:mt-0">
                    <div class="relative flex items-center p-5">
                        <div class="w-12 h-12 image-fit">
                            <div class="font-bold w-12 h-12 bg-primary/20 text-primary flex items-center justify-center rounded-full text-lg border border-primary/20">
                                {{ substr($leakRequest->full_name, 0, 2) }}
                            </div>
                        </div>
                        <div class="ml-4 mr-auto">
                            <div class="font-medium text-base">{{ $leakRequest->full_name }}</div>
                            <div class="text-slate-500">{{ $leakRequest->requester_status ?? 'User' }}</div>
                        </div>
                    </div>
                    <div class="p-5 border-t border-slate-200/60 dark:border-darkmode-400">
                        <div class="flex flex-col justify-center items-center gap-2">
                             @if($leakRequest->status === 'approved')
                                <div class="py-1.5 px-4 rounded-full text-xs font-bold bg-success/20 text-success border border-success/20 flex items-center gap-2 w-full justify-center">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i> ACCESS GRANTED
                                </div>
                            @elseif($leakRequest->status === 'rejected')
                                <div class="py-1.5 px-4 rounded-full text-xs font-bold bg-danger/20 text-danger border border-danger/20 flex items-center gap-2 w-full justify-center">
                                    <i data-lucide="x-circle" class="w-4 h-4"></i> ACCESS DENIED
                                </div>
                            @else
                                <div class="py-1.5 px-4 rounded-full text-xs font-bold bg-warning/20 text-warning border border-warning/20 flex items-center gap-2 w-full justify-center">
                                    <i data-lucide="clock" class="w-4 h-4"></i> PENDING REVIEW
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="p-5 border-t border-slate-200/60 dark:border-darkmode-400">
                        <a class="flex items-center text-primary font-medium" href="mailto:{{ $leakRequest->email }}">
                            <i data-lucide="mail" class="w-4 h-4 mr-2"></i> {{ $leakRequest->email ?? 'N/A' }}
                        </a>
                        <div class="flex items-center mt-5 text-slate-500">
                            <i data-lucide="phone" class="w-4 h-4 mr-2"></i> {{ $leakRequest->phone_number ?? '-' }}
                        </div>
                        <div class="flex items-center mt-5 text-slate-500">
                            <i data-lucide="briefcase" class="w-4 h-4 mr-2"></i> {{ $leakRequest->department ?? '-' }}
                        </div>
                    </div>
                    
                    @if($leakRequest->status === 'pending')
                    <div class="p-5 border-t border-slate-200/60 dark:border-darkmode-400 flex gap-2">
                        <form action="{{ route('leak_request.update_status', $leakRequest->id) }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="status" value="approved">
                             <button class="btn btn-primary w-full shadow-md" type="submit" onclick="return confirm('Are you sure you want to APPROVE this request?')">
                                <i data-lucide="check-circle" class="w-4 h-4 mr-2"></i> Approve
                             </button>
                        </form>
                        <form action="{{ route('leak_request.update_status', $leakRequest->id) }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="status" value="rejected">
                            <button class="btn btn-outline-danger w-full hover:bg-red-50" type="submit" onclick="return confirm('Are you sure you want to REJECT this request?')">
                                <i data-lucide="x-circle" class="w-4 h-4 mr-2"></i> Reject
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            <!-- END: Profile Menu -->
            
            <!-- BEGIN: Profile Content -->
            <div class="col-span-12 lg:col-span-8 2xl:col-span-9">
                <div class="grid grid-cols-12 gap-6">
                    
                    <!-- REQUESTED DATA CONTEXT -->
                    <div class="intro-y box col-span-12">
                        <div class="flex items-center px-5 py-5 sm:py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                            <h2 class="font-medium text-base mr-auto">
                                Target Data Intelligence
                            </h2>
                            <div class="px-2 py-1 rounded bg-slate-100 text-slate-500 text-xs font-medium">Ref ID: #{{ $leakRequest->leakCheckLog->id ?? 'GENERIC' }}</div>
                        </div>
                        <div class="p-5">
                            @if($leakRequest->leakCheckLog)
                                <div class="flex flex-col md:flex-row gap-6 items-start">
                                    <div class="w-full md:w-1/3 p-4 rounded-md bg-slate-50 border border-slate-200 dashed flex flex-col items-center justify-center text-center h-full min-h-[150px]">
                                         <div class="w-16 h-16 rounded-full bg-red-50 text-red-500 flex items-center justify-center mb-3">
                                            <i data-lucide="alert-triangle" class="w-8 h-8"></i>
                                         </div>
                                         <div class="text-2xl font-bold text-slate-700">{{ $leakRequest->leakCheckLog->leak_count ?? 0 }}</div>
                                         <div class="text-xs uppercase font-bold text-slate-400 tracking-wider">Compromised Items</div>
                                    </div>
                                    <div class="w-full md:w-2/3 grid grid-cols-2 gap-4">
                                        <div>
                                            <div class="text-xs text-slate-400 uppercase tracking-wider font-bold mb-1">Target Identity</div>
                                            <div class="text-sm font-medium text-slate-700 break-all">{{ $leakRequest->leakCheckLog->keyword }}</div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-slate-400 uppercase tracking-wider font-bold mb-1">Data Source</div>
                                            <div class="text-sm font-medium text-slate-700">{{ $leakRequest->leakCheckLog->source ?? 'Unknown' }}</div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-slate-400 uppercase tracking-wider font-bold mb-1">Detection Date</div>
                                            <div class="text-sm font-medium text-slate-700">{{ $leakRequest->leakCheckLog->created_at->format('d M Y H:i A') }}</div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-slate-400 uppercase tracking-wider font-bold mb-1">Risk Level</div>
                                            <span class="text-xs font-bold text-danger">CRITICAL EXPOSURE</span>
                                        </div>
                                         <div class="col-span-2 mt-2">
                                            <a href="{{ route('leak_check.show_log', $leakRequest->leakCheckLog->id) }}" class="text-primary text-xs font-bold hover:underline flex items-center">
                                                View Source Log <i data-lucide="external-link" class="w-3 h-3 ml-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center py-10 text-center">
                                    <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mb-4">
                                        <i data-lucide="search" class="w-8 h-8"></i>
                                    </div>
                                    <h3 class="text-lg font-medium text-slate-700">General Information Request</h3>
                                    <p class="text-slate-500 max-w-sm mt-2">This request is not linked to a specific leak log ID. The requester is inquiring about:</p>
                                    <div class="mt-4 px-4 py-2 bg-slate-100 rounded-md font-mono text-primary font-bold">
                                        {{ $leakRequest->query }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- JUSTIFICATION -->
                    <div class="intro-y box col-span-12">
                         <div class="flex items-center px-5 py-5 sm:py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                            <h2 class="font-medium text-base mr-auto">
                                Justification & Purpose
                            </h2>
                        </div>
                        <div class="p-5">
                            <div class="text-slate-600 leading-relaxed text-sm">
                                <span class="text-3xl font-serif text-slate-300 mr-2 float-left -mt-2">“</span>
                                {{ $leakRequest->reason }}
                                <span class="text-3xl font-serif text-slate-300 ml-2 float-right -mt-4">”</span>
                            </div>
                        </div>
                    </div>

                    @if($leakRequest->leakCheckLog && $leakRequest->leakCheckLog->raw_response)
                        <!-- RAW DATA PAYLOAD -->
                        <div class="intro-y box col-span-12 overflow-hidden shadow-sm">
                            <div class="border-b border-dashed border-slate-200/60 dark:border-darkmode-400 px-5 py-4 flex flex-col sm:flex-row items-center gap-4 bg-[rgb(249,250,251)]/60 dark:bg-darkmode-600/50">
                                <div class="flex items-center mr-auto">
                                    <h2 class="font-medium text-base text-slate-700 dark:text-slate-200">
                                        Target Data
                                    </h2>
                                    <div class="hidden sm:flex items-center ml-3 px-2 py-0.5 rounded-full bg-slate-200/60 dark:bg-darkmode-400 text-slate-500 text-xs font-medium border border-slate-200 dark:border-darkmode-300">
                                        {{ $items->total() }} records
                                    </div>
                                </div>
                                
                                <form method="GET" action="{{ route('leak_request.show', $leakRequest->id) }}" class="w-full sm:w-auto flex items-center gap-3">
                                    <div class="relative w-full sm:w-72">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <i data-lucide="search" class="w-4 h-4 text-slate-400"></i>
                                        </div>
                                        <input type="text" 
                                               name="search" 
                                               class="w-full pl-10 pr-4 py-2 text-sm text-slate-700 bg-white border border-slate-200/80 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all shadow-sm dark:bg-darkmode-800 dark:border-darkmode-400 dark:text-slate-200 dark:placeholder-slate-500" 
                                               placeholder="Search across identity, source..." 
                                               value="{{ $search ?? '' }}">
                                        @if($search)
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-2">
                                                <a href="{{ route('leak_request.show', $leakRequest->id) }}" class="p-1 rounded-full hover:bg-slate-100 text-slate-400 hover:text-slate-600 dark:hover:bg-darkmode-700 transition-colors">
                                                    <i data-lucide="x-circle" class="w-4 h-4"></i>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                    <button class="btn btn-outline-secondary hidden sm:flex border-slate-200/60 hover:bg-slate-50 text-slate-500 shadow-sm">
                                        <i data-lucide="filter" class="w-4 h-4 mr-2"></i> Filters
                                    </button>
                                </form>
                            </div>
                            
                            <div class="overflow-x-auto bg-white dark:bg-darkmode-600">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr>
                                            <th class="py-4 px-5 border-b border-dashed border-slate-200/60 dark:border-darkmode-400 w-10 text-center bg-slate-50/40 dark:bg-darkmode-800/30">
                                                <input type="checkbox" class="form-check-input border-slate-300 shadow-sm rounded-[4px]">
                                            </th>
                                            <th class="py-4 px-5 border-b border-dashed border-slate-200/60 dark:border-darkmode-400 font-semibold text-[11px] text-slate-500 uppercase tracking-widest bg-slate-50/40 dark:bg-darkmode-800/30">
                                                Identity & Source
                                            </th>
                                            <th class="py-4 px-5 border-b border-dashed border-slate-200/60 dark:border-darkmode-400 font-semibold text-[11px] text-slate-500 text-center uppercase tracking-widest bg-slate-50/40 dark:bg-darkmode-800/30">
                                                Status
                                            </th>
                                            <th class="py-4 px-5 border-b border-dashed border-slate-200/60 dark:border-darkmode-400 font-semibold text-[11px] text-slate-500 uppercase tracking-widest bg-slate-50/40 dark:bg-darkmode-800/30">
                                                Metadata
                                            </th>
                                            <th class="py-4 px-5 border-b border-dashed border-slate-200/60 dark:border-darkmode-400 font-semibold text-[11px] text-slate-500 text-center uppercase tracking-widest w-24 bg-slate-50/40 dark:bg-darkmode-800/30">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($items as $index => $item)
                                            <tr class="group hover:bg-blue-50/30 dark:hover:bg-darkmode-500/50 transition-colors border-b border-dashed border-slate-200/60 dark:border-darkmode-400 last:border-0">
                                                <td class="py-4 px-5 text-center">
                                                    <input type="checkbox" class="form-check-input border-slate-300 shadow-sm rounded-[4px]">
                                                </td>
                                                <td class="py-4 px-5">
                                                    <div class="flex items-center">
                                                        <div class="relative flex-none w-9 h-9 image-fit">
                                                            <div class="rounded-full border-[2px] border-white dark:border-darkmode-600 shadow-md bg-indigo-50 flex items-center justify-center text-indigo-500 dark:bg-darkmode-400 dark:text-slate-300 w-full h-full overflow-hidden">
                                                                <span class="font-bold text-xs">{{ substr($item['identity'], 0, 1) }}</span>
                                                            </div>
                                                            @if($item['source_name'] === 'Collection1')
                                                                <div class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-white rounded-full"></div>
                                                            @else
                                                                <div class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-blue-500 border-2 border-white rounded-full"></div>
                                                            @endif
                                                        </div>
                                                        <div class="ml-4 mr-auto">
                                                            <div class="font-medium text-slate-700 dark:text-slate-200 text-sm">
                                                                {{ $item['identity'] }}
                                                            </div>
                                                            <div class="text-slate-400 text-[11px] mt-0.5 flex items-center font-medium">
                                                                {{ $item['source_name'] }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                
                                                <td class="py-4 px-5 text-center">
                                                    @if($item['password'])
                                                        <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-600 border border-red-100">
                                                            Leaked
                                                        </div>
                                                    @else
                                                        <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-600 border border-green-100">
                                                            Safe
                                                        </div>
                                                    @endif
                                                </td>
                                                
                                                <td class="py-4 px-5">
                                                    <div class="flex flex-wrap gap-1.5 items-center">
                                                        @if(count($item['other_data']) > 0)
                                                            @foreach(array_slice($item['other_data'], 0, 2) as $k => $v)
                                                                @if(!empty($v) && $v !== '0')
                                                                    <div class="flex items-center text-[10px] text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-darkmode-700 px-2 py-1 rounded border border-slate-200/80 dark:border-darkmode-500 max-w-[140px]">
                                                                        <span class="font-bold uppercase mr-1">{{ $k }}</span>
                                                                        <span class="truncate">{{ Str::limit(is_string($v) ? $v : json_encode($v), 10) }}</span>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                            @if(count($item['other_data']) > 2)
                                                                <span class="text-[10px] bg-slate-50 text-slate-400 px-1.5 py-0.5 rounded border border-slate-100">+{{ count($item['other_data']) - 2 }}</span>
                                                            @endif
                                                        @else
                                                            <span class="text-slate-300 text-xs">-</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                
                                                <td class="py-4 px-5">
                                                    <div class="flex justify-center items-center gap-2">
                                                        <button 
                                                           data-tw-toggle="modal" 
                                                           data-tw-target="#detail-modal"
                                                           data-source="{{ $item['source_name'] }}"
                                                           data-info="{{ $item['source_info'] }}"
                                                           data-identity="{{ $item['identity'] }}"
                                                           data-password="{{ $item['password'] ?? 'NO_PASSWORD' }}"
                                                           data-meta="{{ json_encode($item['other_data']) }}"
                                                           class="view-detail-btn w-8 h-8 rounded-lg flex items-center justify-center border border-slate-200 text-slate-400 hover:bg-slate-50 hover:text-slate-600 transition-all shadow-sm"
                                                           title="View Details">
                                                            <i data-lucide="more-horizontal" class="w-4 h-4"></i>
                                                        </button>
                                                        
                                                        @if($item['password'])
                                                            <button class="password-toggle w-8 h-8 rounded-lg flex items-center justify-center border border-slate-200 text-slate-400 hover:bg-red-50 hover:text-red-500 hover:border-red-100 transition-all shadow-sm relative group" title="Reveal Password">
                                                                <i data-lucide="key" class="w-4 h-4 group-[.revealed]:hidden"></i>
                                                                <i data-lucide="eye-off" class="w-4 h-4 hidden group-[.revealed]:block"></i>
                                                                <div class="hidden password-value absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] px-2 py-1 rounded shadow-lg whitespace-nowrap z-[100] font-mono" data-value="{{ $item['password'] }}"></div>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="py-16 text-center text-slate-500">
                                                    <div class="flex flex-col items-center justify-center">
                                                         <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-300">
                                                            <i data-lucide="search" class="w-6 h-6"></i>
                                                         </div>
                                                         <p class="text-slate-500 text-sm">No results found for "<span class="font-medium text-slate-600">{{ $search }}</span>"</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- MODERN PAGINATION -->
                            <div class="px-5 py-4 flex flex-col sm:flex-row items-center justify-between border-t border-dashed border-slate-200/60 dark:border-darkmode-400 bg-slate-50/30">
                                <div class="text-slate-500 text-xs font-medium">
                                    Showing {{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }} of {{ $items->total() }}
                                </div>
                                <div class="mt-2 sm:mt-0">
                                    {{ $items->onEachSide(1)->links('pagination::tailwind') }}
                                </div>
                            </div>
                        </div>
                    @endif

                     <!-- ACTIVITY / META -->
                    <div class="intro-y box col-span-12">
                         <div class="flex items-center px-5 py-5 sm:py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                            <h2 class="font-medium text-base mr-auto">
                                Audit Trail
                            </h2>
                        </div>
                        <div class="p-5">
                            <ol class="relative border-l border-slate-200 ml-3">                  
                                <li class="mb-10 ml-6">            
                                    <span class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full -left-3 ring-8 ring-white">
                                        <i data-lucide="send" class="w-3 h-3 text-blue-800"></i>
                                    </span>
                                    <h3 class="flex items-center mb-1 text-sm font-semibold text-slate-900">Request Submitted</h3>
                                    <time class="block mb-2 text-xs font-normal leading-none text-slate-400">Created on {{ $leakRequest->created_at->format('F d, Y at H:i') }}</time>
                                    <p class="text-xs font-normal text-slate-500">User {{ $leakRequest->full_name }} initiated access request for restricted level 3 data.</p>
                                </li>
                                @if($leakRequest->status !== 'pending')
                                <li class="ml-6">
                                    @if($leakRequest->status == 'approved')
                                        <span class="absolute flex items-center justify-center w-6 h-6 bg-green-100 rounded-full -left-3 ring-8 ring-white">
                                            <i data-lucide="check" class="w-3 h-3 text-green-800"></i>
                                        </span>
                                        <h3 class="mb-1 text-sm font-semibold text-slate-900">Approved</h3>
                                        <time class="block mb-2 text-xs font-normal leading-none text-slate-400">Updated on {{ $leakRequest->updated_at->format('F d, Y at H:i') }}</time>
                                    @else
                                         <span class="absolute flex items-center justify-center w-6 h-6 bg-red-100 rounded-full -left-3 ring-8 ring-white">
                                            <i data-lucide="x" class="w-3 h-3 text-red-800"></i>
                                        </span>
                                        <h3 class="mb-1 text-sm font-semibold text-slate-900">Rejected</h3>
                                        <time class="block mb-2 text-xs font-normal leading-none text-slate-400">Updated on {{ $leakRequest->updated_at->format('F d, Y at H:i') }}</time>
                                    @endif
                                </li>
                                @endif
                            </ol>
                        </div>
                    </div>
                </div>
            <!-- END: Profile Content -->
        </div>
    </div>

    {{-- VIEW DETAIL MODAL (COPIED FROM LEAK CHECK SHOW) --}}
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
