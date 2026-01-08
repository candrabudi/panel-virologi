@extends('layouts.app')
@section('title', 'Leak Intelligence: Request Logs')
@section('content')
    <div class="col-span-12">
        <div class="flex flex-col gap-y-3 md:h-10 md:flex-row md:items-center">
            <div class="text-base font-medium group-[.mode--light]:text-white flex items-center gap-2">
                <i data-lucide="database" class="w-5 h-5 text-rose-500"></i>
                Intelligence Audit Trail
            </div>
            <div class="flex flex-col gap-x-3 gap-y-2 md:ml-auto md:flex-row">
                <a href="{{ route('leak_check.print') }}" target="_blank" class="transition-all active:scale-95 inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-slate-600 bg-white dark:bg-darkmode-600 dark:text-slate-300 rounded-lg shadow-sm hover:bg-slate-50 dark:hover:bg-darkmode-500 border border-slate-200 dark:border-darkmode-400">
                    <i data-lucide="printer" class="w-4 h-4 mr-2"></i>
                    Print / PDF
                </a>
                <a href="{{ route('leak_check.export') }}" class="transition-all active:scale-95 inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-primary rounded-lg shadow-lg shadow-primary/20 hover:bg-primary/90">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4 mr-2"></i>
                    Export CSV
                </a>
            </div>
        </div>

        {{-- AUDIT STATS --}}
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="col-span-12 sm:col-span-4 box p-6 border-none shadow-sm dark:bg-darkmode-600 p-5">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center text-primary">
                        <i data-lucide="activity" class="w-6 h-6"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-2xl font-bold dark:text-slate-200">{{ number_format($logs->total()) }}</div>
                        <div class="text-xs text-slate-500 font-medium uppercase tracking-wider">Total Scans</div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-4 box p-6 border-none shadow-sm dark:bg-darkmode-600 p-5">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-rose-500/10 rounded-xl flex items-center justify-center text-rose-500">
                        <i data-lucide="alert-octagon" class="w-6 h-6 text-rose-500"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-2xl font-bold dark:text-slate-200">{{ number_format($logs->where('leak_count', '>', 0)->count()) }}</div>
                        <div class="text-xs text-slate-500 font-medium uppercase tracking-wider">Breaches Detected</div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-4 box p-6 border-none shadow-sm bg-slate-900 text-white p-5">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center text-white">
                        <i data-lucide="shield-check" class="w-6 h-6"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-2xl font-bold">100%</div>
                        <div class="text-xs text-white/60 font-medium uppercase tracking-wider">Authorized Feed</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box--stacked mt-6 p-0 overflow-hidden bg-white dark:bg-darkmode-600 p-5">
            <div class="flex flex-col sm:flex-row items-center p-6 border-b border-slate-100 dark:border-darkmode-400">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-slate-50 dark:bg-darkmode-400 rounded-lg flex items-center justify-center text-slate-400">
                        <i data-lucide="list" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <div class="text-base font-bold text-slate-700 dark:text-slate-200">Security Event Logs</div>
                        <div class="text-[10px] text-slate-400 font-medium uppercase tracking-[0.2em] mt-0.5">Real-time Intelligence Stream</div>
                    </div>
                </div>
            </div>

            <div class="overflow-auto xl:overflow-visible">
                <table class="w-full text-left border-b border-slate-200/60">
                    <thead>
                        <tr>
                            <td class="px-5 border-b dark:border-darkmode-300 border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500">
                                Initiator
                            </td>
                            <td class="px-5 border-b dark:border-darkmode-300 border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500">
                                Target Query
                            </td>
                            <td class="px-5 border-b dark:border-darkmode-300 border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500 text-center">
                                Results
                            </td>
                            <td class="px-5 border-b dark:border-darkmode-300 border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500">
                                Date Created
                            </td>
                            <td class="px-5 border-b dark:border-darkmode-300 border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500 text-center">
                                Status
                            </td>
                            <td class="px-5 border-b dark:border-darkmode-300 border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500 text-center">
                                Action
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr class="[&_td]:last:border-b-0">
                                <td class="px-5 border-b border-dashed py-4 dark:bg-darkmode-600">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 image-fit zoom-in -ml-5 first:ml-0">
                                            <div class="rounded-full bg-slate-100 dark:bg-darkmode-400 flex items-center justify-center w-full h-full text-primary border border-slate-200 dark:border-darkmode-400 font-bold text-xs">
                                                {{ substr($log->user->username ?? 'GS', 0, 2) }}
                                            </div>
                                        </div>
                                        <div class="ml-3.5">
                                            <div class="whitespace-nowrap font-medium text-slate-700 dark:text-slate-200">
                                                {{ $log->user->username ?? 'Guest' }}
                                            </div>
                                            <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">
                                                Internal User
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 border-b border-dashed py-4 dark:bg-darkmode-600">
                                    <div class="whitespace-nowrap font-medium text-slate-700 dark:text-slate-200">
                                        {{ $log->query }}
                                    </div>
                                    <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">
                                        {{ $log->ip_address }}
                                    </div>
                                </td>
                                <td class="px-5 border-b border-dashed py-4 dark:bg-darkmode-600 text-center">
                                    <div class="flex items-center justify-center">
                                        @if($log->leak_count > 0)
                                            <div class="text-rose-500 font-medium text-sm flex items-center">
                                                <i data-lucide="alert-circle" class="w-3.5 h-3.5 mr-1.5"></i>
                                                {{ number_format($log->leak_count) }}
                                            </div>
                                        @else
                                            <div class="text-slate-400 font-medium text-sm">
                                                0
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 border-b border-dashed py-4 dark:bg-darkmode-600">
                                    <div class="whitespace-nowrap text-slate-500 text-sm">
                                        {{ $log->created_at->format('M d, Y') }}
                                    </div>
                                    <div class="mt-0.5 whitespace-nowrap text-xs text-slate-400">
                                        {{ $log->created_at->format('H:i:s') }}
                                    </div>
                                </td>
                                <td class="px-5 border-b border-dashed py-4 dark:bg-darkmode-600 text-center">
                                    @if($log->status == 'success')
                                        <div class="flex items-center justify-center text-success">
                                            <i data-lucide="check-square" class="w-4 h-4 mr-2 stroke-[1.3]"></i> OK
                                        </div>
                                    @else
                                        <div class="flex items-center justify-center text-danger">
                                            <i data-lucide="x-square" class="w-4 h-4 mr-2 stroke-[1.3]"></i> Failed
                                        </div>
                                    @endif
                                </td>
                                <td class="px-5 border-b border-dashed py-4 dark:bg-darkmode-600 relative text-center">
                                    <a href="{{ route('leak_check.show_log', $log->id) }}" class="flex items-center justify-center text-slate-500 hover:text-primary transition-colors">
                                        <i data-lucide="check-square" class="w-4 h-4 stroke-[1.3]"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-4 text-center text-slate-500">
                                    No logs found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
                <div class="p-6 border-t border-slate-100 dark:border-darkmode-400 bg-slate-50/30">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
@endpush
