@extends('layouts.app')
@section('title', 'Leak Intelligence: Request Logs')

@section('content')
    @php
        $pageLogs = $logs->getCollection();
        $pageBreaches = $pageLogs->filter(fn ($log) => (int) $log->leak_count > 0)->count();
        $pageFailures = $pageLogs->filter(fn ($log) => $log->status !== 'success')->count();
        $rangeStart = $logs->firstItem() ?? 0;
        $rangeEnd = $logs->lastItem() ?? 0;
    @endphp

    <div class="col-span-12">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div class="max-w-2xl">
                <h1 class="text-2xl font-semibold text-slate-800 dark:text-slate-100">
                    Leak check logs
                </h1>
                <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">
                    Review scan requests, detected results, and processing status.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2 sm:justify-end">
                <a href="{{ route('leak_check.print') }}" target="_blank" rel="noopener"
                    class="inline-flex min-h-10 items-center justify-center gap-2 rounded-md border border-slate-200 bg-white px-3.5 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary dark:border-darkmode-400 dark:bg-darkmode-600 dark:text-slate-200 dark:hover:bg-darkmode-500">
                    <i data-lucide="printer" class="h-4 w-4" aria-hidden="true"></i>
                    Print / PDF
                </a>
                <a href="{{ route('leak_check.export') }}"
                    class="inline-flex min-h-10 items-center justify-center gap-2 rounded-md bg-primary px-3.5 py-2 text-sm font-medium text-white transition-colors hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2">
                    <i data-lucide="download" class="h-4 w-4" aria-hidden="true"></i>
                    Export CSV
                </a>
            </div>
        </div>

        <dl class="mt-6 grid grid-cols-1 overflow-hidden rounded-lg border border-slate-200/70 bg-white sm:grid-cols-3 dark:border-darkmode-400 dark:bg-darkmode-600">
            <div class="px-5 py-4">
                <dt class="text-sm text-slate-500 dark:text-slate-400">Total scans</dt>
                <dd class="mt-1 text-2xl font-semibold tabular-nums text-slate-800 dark:text-slate-100">
                    {{ number_format($logs->total()) }}
                </dd>
                <p class="mt-0.5 text-xs text-slate-400 dark:text-slate-500">Across all pages</p>
            </div>
            <div class="border-t border-slate-200/70 px-5 py-4 sm:border-l sm:border-t-0 dark:border-darkmode-400">
                <dt class="text-sm text-slate-500 dark:text-slate-400">Breaches detected</dt>
                <dd class="mt-1 text-2xl font-semibold tabular-nums text-rose-600 dark:text-rose-400">
                    {{ number_format($pageBreaches) }}
                </dd>
                <p class="mt-0.5 text-xs text-slate-400 dark:text-slate-500">On this page</p>
            </div>
            <div class="border-t border-slate-200/70 px-5 py-4 sm:border-l sm:border-t-0 dark:border-darkmode-400">
                <dt class="text-sm text-slate-500 dark:text-slate-400">Failed requests</dt>
                <dd class="mt-1 text-2xl font-semibold tabular-nums text-slate-800 dark:text-slate-100">
                    {{ number_format($pageFailures) }}
                </dd>
                <p class="mt-0.5 text-xs text-slate-400 dark:text-slate-500">On this page</p>
            </div>
        </dl>

        <section class="mt-6 overflow-hidden rounded-lg border border-slate-200/70 bg-white dark:border-darkmode-400 dark:bg-darkmode-600"
            aria-labelledby="request-history-title">
            <div class="flex flex-col gap-1 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 id="request-history-title" class="text-base font-semibold text-slate-800 dark:text-slate-100">
                        Request history
                    </h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Showing {{ number_format($rangeStart) }}–{{ number_format($rangeEnd) }} of {{ number_format($logs->total()) }} scans
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left" style="min-width: 920px">
                    <thead>
                        <tr class="text-sm text-slate-500 dark:text-slate-400">
                            <th scope="col" class="whitespace-nowrap border-y border-slate-200/70 px-5 py-3 font-medium dark:border-darkmode-400">
                                Initiator
                            </th>
                            <th scope="col" class="border-y border-slate-200/70 px-5 py-3 font-medium dark:border-darkmode-400">
                                Target query / source IP
                            </th>
                            <th scope="col" class="whitespace-nowrap border-y border-slate-200/70 px-5 py-3 text-right font-medium dark:border-darkmode-400">
                                Results
                            </th>
                            <th scope="col" class="whitespace-nowrap border-y border-slate-200/70 px-5 py-3 font-medium dark:border-darkmode-400">
                                Date created
                            </th>
                            <th scope="col" class="whitespace-nowrap border-y border-slate-200/70 px-5 py-3 font-medium dark:border-darkmode-400">
                                Status
                            </th>
                            <th scope="col" class="whitespace-nowrap border-y border-slate-200/70 px-5 py-3 text-right font-medium dark:border-darkmode-400">
                                Details
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            @php
                                $initiator = $log->user->username ?? 'Guest';
                            @endphp
                            <tr class="text-sm text-slate-700 dark:text-slate-200">
                                <td class="border-b border-slate-100 px-5 py-3.5 dark:border-darkmode-400">
                                    <div class="flex min-w-0 items-center gap-3">
                                        <span class="flex h-8 w-8 flex-none items-center justify-center rounded-full bg-slate-100 text-xs font-semibold text-slate-600 dark:bg-darkmode-400 dark:text-slate-200" aria-hidden="true">
                                            {{ strtoupper(substr($initiator, 0, 2)) }}
                                        </span>
                                        <div class="min-w-0">
                                            <div class="truncate font-medium" title="{{ $initiator }}">{{ $initiator }}</div>
                                            <div class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                                                {{ $log->user ? 'Account' : 'Guest request' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="border-b border-slate-100 px-5 py-3.5 dark:border-darkmode-400">
                                    <div class="max-w-sm truncate font-medium" title="{{ $log->query }}">
                                        {{ $log->query ?: '—' }}
                                    </div>
                                    <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500 dark:text-slate-400">
                                        {{ $log->ip_address ?: '—' }}
                                    </div>
                                </td>
                                <td class="border-b border-slate-100 px-5 py-3.5 text-right tabular-nums dark:border-darkmode-400">
                                    <span class="font-medium {{ $log->leak_count > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-500 dark:text-slate-400' }}">
                                        {{ number_format($log->leak_count) }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap border-b border-slate-100 px-5 py-3.5 dark:border-darkmode-400">
                                    <div>{{ $log->created_at->format('M d, Y') }}</div>
                                    <div class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                                        {{ $log->created_at->format('H:i:s') }}
                                    </div>
                                </td>
                                <td class="border-b border-slate-100 px-5 py-3.5 dark:border-darkmode-400">
                                    @if ($log->status === 'success')
                                        <span class="inline-flex flex-none items-center gap-1.5 whitespace-nowrap rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-600 dark:bg-emerald-400" aria-hidden="true"></span>
                                            Success
                                        </span>
                                    @else
                                        <span class="inline-flex flex-none items-center gap-1.5 whitespace-nowrap rounded-full bg-rose-50 px-2.5 py-1 text-xs font-medium text-rose-800 dark:bg-rose-900/30 dark:text-rose-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-rose-600 dark:bg-rose-400" aria-hidden="true"></span>
                                            Failed
                                        </span>
                                    @endif
                                </td>
                                <td class="border-b border-slate-100 px-5 py-3.5 text-right dark:border-darkmode-400">
                                    <a href="{{ route('leak_check.show_log', $log->id) }}"
                                        class="inline-flex h-8 items-center justify-center gap-1.5 rounded px-2 text-sm font-medium text-slate-600 transition-colors hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary dark:text-slate-300"
                                        aria-label="View details for scan {{ $log->id }}" title="View details">
                                        View
                                        <i data-lucide="arrow-up-right" class="h-4 w-4" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center">
                                    <p class="font-medium text-slate-700 dark:text-slate-200">No scans recorded yet</p>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                        Leak check requests will appear here after the first scan.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($logs->hasPages())
                <div class="border-t border-slate-200/70 px-5 py-4 dark:border-darkmode-400">
                    {{ $logs->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection
