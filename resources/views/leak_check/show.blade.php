@extends('layouts.app')
@section('title', 'Leak Intelligence: Scan Details')

@push('styles')
    <style>
        .leak-check-detail .scan-detail-icon {
            display: block;
            width: 1rem;
            height: 1rem;
            flex: none;
            stroke-width: 1.75;
        }

        .leak-check-detail .result-status {
            display: inline-flex;
            align-items: center;
            gap: .375rem;
            border-radius: 9999px;
            padding: .25rem .625rem;
            font-size: .75rem;
            font-weight: 500;
            line-height: 1rem;
            white-space: nowrap;
        }

        .leak-check-detail .result-status-dot {
            width: .375rem;
            height: .375rem;
            flex: none;
            border-radius: 9999px;
        }

        .leak-check-detail .result-status--leaked {
            color: #9f1239;
            background: #fff1f2;
        }

        .leak-check-detail .result-status--leaked .result-status-dot {
            background: #e11d48;
        }

        .leak-check-detail .result-status--safe {
            color: #166534;
            background: #f0fdf4;
        }

        .leak-check-detail .result-status--safe .result-status-dot {
            background: #16a34a;
        }

        .dark .leak-check-detail .result-status--leaked {
            color: #fda4af;
            background: rgb(159 18 57 / 24%);
        }

        .dark .leak-check-detail .result-status--leaked .result-status-dot {
            background: #fb7185;
        }

        .dark .leak-check-detail .result-status--safe {
            color: #86efac;
            background: rgb(22 101 52 / 24%);
        }

        .dark .leak-check-detail .result-status--safe .result-status-dot {
            background: #4ade80;
        }

        .leak-check-detail .scan-reveal-button {
            border: 1px solid #fecdd3;
        }

        .dark .leak-check-detail .scan-reveal-button {
            border-color: rgb(190 24 93 / 40%);
        }

        .leak-check-detail .scan-results-header {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.25rem;
        }

        .leak-check-detail .scan-results-filter-wrap {
            position: relative;
            width: 100%;
            min-width: 0;
        }

        .leak-check-detail .scan-detail-header {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            grid-template-areas:
                "back"
                "heading"
                "action";
            gap: .75rem;
            align-items: center;
        }

        .leak-check-detail .scan-detail-back {
            grid-area: back;
            display: inline-flex;
            width: fit-content;
            min-height: 2.25rem;
            align-items: center;
            gap: .5rem;
            margin: 0;
            padding: 0 .5rem;
        }

        .leak-check-detail .scan-detail-heading {
            grid-area: heading;
            min-width: 0;
        }

        .leak-check-detail .scan-detail-heading h1 {
            margin: 0;
            line-height: 1.2;
        }

        .leak-check-detail .scan-detail-query {
            margin: .375rem 0 0;
            font-size: .875rem;
            line-height: 1.25rem;
            overflow-wrap: anywhere;
        }

        .leak-check-detail .scan-results-filter {
            display: block;
            box-sizing: border-box;
            width: 100%;
            height: 2.5rem;
            margin: 0;
            border: 1px solid #cbd5e1;
            border-radius: .375rem;
            padding: 0 .75rem 0 2.5rem;
            background: #fff;
            color: #334155;
            font: inherit;
            font-size: .875rem;
            line-height: 1.25rem;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .leak-check-detail .scan-results-filter::placeholder {
            color: #94a3b8;
            opacity: 1;
        }

        .leak-check-detail .scan-results-filter:focus {
            border-color: #6366f1;
            outline: none;
            box-shadow: 0 0 0 3px rgb(99 102 241 / 16%);
        }

        .leak-check-detail .scan-search-icon {
            position: absolute;
            top: 50%;
            left: .75rem;
            width: 1rem;
            height: 1rem;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
        }

        .dark .leak-check-detail .scan-results-filter {
            border-color: #475569;
            background: #1e293b;
            color: #e2e8f0;
        }

        .dark .leak-check-detail .scan-results-filter::placeholder {
            color: #64748b;
        }

        .dark .leak-check-detail .scan-results-filter:focus {
            border-color: #818cf8;
            box-shadow: 0 0 0 3px rgb(129 140 248 / 20%);
        }

        .leak-check-detail .scan-download-button {
            grid-area: action;
            justify-self: start;
            height: 2.5rem;
            min-height: 2.5rem;
        }

        .leak-check-detail .scan-table-hint {
            margin: 0;
            padding: .5rem 1.25rem;
            color: #64748b;
            font-size: .75rem;
        }

        @media (min-width: 640px) {
            .leak-check-detail .scan-detail-header {
                grid-template-columns: minmax(0, 1fr) auto;
                grid-template-areas:
                    "back ."
                    "heading action";
                column-gap: 1.5rem;
                row-gap: .5rem;
            }

            .leak-check-detail .scan-download-button {
                align-self: center;
                justify-self: end;
            }

            .leak-check-detail .scan-results-header {
                grid-template-columns: minmax(0, 1fr) 20rem;
            }

            .leak-check-detail .scan-result-range {
                white-space: nowrap;
            }

            .leak-check-detail .scan-table-hint {
                display: none;
            }
        }
    </style>
@endpush

@section('content')
    <div class="col-span-12 leak-check-detail">
        <header class="scan-detail-header">
            <a href="{{ route('leak_check.logs') }}"
                class="scan-detail-back text-sm font-medium text-slate-600 transition-colors hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary dark:text-slate-300"
                aria-label="Back to leak check logs">
                <i data-lucide="arrow-left" class="scan-detail-icon" aria-hidden="true"></i>
                Back to logs
            </a>

            <div class="scan-detail-heading">
                <h1 class="text-2xl font-semibold text-slate-800 dark:text-slate-100">
                    Scan details
                </h1>
                <p class="scan-detail-query text-slate-500 dark:text-slate-400">
                    {{ $log->query ?: 'No target query recorded' }}
                </p>
            </div>

            <a href="{{ route('leak_check.download_json', $log->id) }}"
                class="scan-download-button inline-flex flex-none items-center justify-center gap-2 rounded-md bg-primary px-3.5 py-2 text-sm font-medium text-white transition-colors hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2">
                <i data-lucide="download" class="scan-detail-icon" aria-hidden="true"></i>
                Download JSON
            </a>
        </header>

        <dl class="mt-6 grid grid-cols-2 gap-px overflow-hidden rounded-lg border border-slate-200/70 bg-slate-200/70 sm:grid-cols-4 dark:border-darkmode-400 dark:bg-darkmode-400">
            <div class="min-w-0 bg-white px-4 py-4 dark:bg-darkmode-600 sm:px-5">
                <dt class="text-sm text-slate-500 dark:text-slate-400">Detected records</dt>
                <dd class="mt-1 text-xl font-semibold tabular-nums text-slate-800 dark:text-slate-100">
                    {{ number_format($log->leak_count) }}
                </dd>
            </div>
            <div class="min-w-0 bg-white px-4 py-4 dark:bg-darkmode-600 sm:px-5">
                <dt class="text-sm text-slate-500 dark:text-slate-400">Scan status</dt>
                <dd class="mt-1 text-base font-medium text-slate-800 dark:text-slate-100">
                    {{ $log->status === 'success' ? 'Success' : 'Failed' }}
                </dd>
            </div>
            <div class="min-w-0 bg-white px-4 py-4 dark:bg-darkmode-600 sm:px-5">
                <dt class="text-sm text-slate-500 dark:text-slate-400">Checked on</dt>
                <dd class="mt-1 text-sm font-medium text-slate-800 dark:text-slate-100">
                    {{ $log->created_at->format('M d, Y') }}
                    <span class="mt-0.5 block text-xs font-normal text-slate-500 dark:text-slate-400">
                        {{ $log->created_at->format('H:i:s') }}
                    </span>
                </dd>
            </div>
            <div class="min-w-0 bg-white px-4 py-4 dark:bg-darkmode-600 sm:px-5">
                <dt class="text-sm text-slate-500 dark:text-slate-400">Source IP</dt>
                <dd class="mt-1 break-all text-sm font-medium text-slate-800 dark:text-slate-100">
                    {{ $log->ip_address ?: '-' }}
                </dd>
            </div>
        </dl>

        <section class="mt-6 overflow-hidden rounded-lg border border-slate-200/70 bg-white dark:border-darkmode-400 dark:bg-darkmode-600"
            aria-labelledby="scan-results-title">
            <div class="scan-results-header">
                <div>
                    <h2 id="scan-results-title" class="text-base font-semibold text-slate-800 dark:text-slate-100">
                        Scan results
                    </h2>
                    <p class="scan-result-range mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Showing {{ number_format($items->firstItem() ?? 0) }}&ndash;{{ number_format($items->lastItem() ?? 0) }} of {{ number_format($items->total()) }} records
                    </p>
                </div>

                <div class="scan-results-filter-wrap">
                    <i data-lucide="search" class="scan-detail-icon scan-search-icon" aria-hidden="true"></i>
                    <input id="scan-results-filter" type="search" autocomplete="off"
                        aria-label="Filter current page results"
                        placeholder="Filter this page"
                        class="scan-results-filter">
                </div>
            </div>

            <p class="scan-table-hint">Scroll horizontally to view all columns</p>
            <div class="overflow-x-auto">
                <table class="w-full text-left" style="min-width: 1040px">
                    <thead>
                        <tr class="text-sm text-slate-500 dark:text-slate-400">
                            <th scope="col" class="whitespace-nowrap border-y border-slate-200/70 px-5 py-3 font-medium dark:border-darkmode-400">
                                Source
                            </th>
                            <th scope="col" class="border-y border-slate-200/70 px-5 py-3 font-medium dark:border-darkmode-400">
                                Identity
                            </th>
                            <th scope="col" class="whitespace-nowrap border-y border-slate-200/70 px-5 py-3 font-medium dark:border-darkmode-400">
                                Result
                            </th>
                            <th scope="col" class="border-y border-slate-200/70 px-5 py-3 font-medium dark:border-darkmode-400">
                                Other data
                            </th>
                            <th scope="col" class="whitespace-nowrap border-y border-slate-200/70 px-5 py-3 text-right font-medium dark:border-darkmode-400">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody id="scan-results-body">
                        @forelse ($items as $item)
                            @php
                                $hasPassword = isset($item['password']) && $item['password'] !== null && $item['password'] !== '';
                                $otherData = collect($item['other_data'] ?? [])->filter(function ($value) {
                                    return $value !== null && $value !== '' && $value !== '0';
                                });
                                $visibleOtherData = $otherData->take(3);
                            @endphp
                            <tr class="result-row text-sm text-slate-700 dark:text-slate-200">
                                <td class="border-b border-slate-100 px-5 py-3.5 dark:border-darkmode-400">
                                    <div class="flex min-w-0 items-center gap-3">
                                        <span class="flex h-8 w-8 flex-none items-center justify-center rounded-full bg-slate-100 text-slate-500 dark:bg-darkmode-400 dark:text-slate-300" aria-hidden="true">
                                            <i data-lucide="database" class="scan-detail-icon"></i>
                                        </span>
                                        <div class="min-w-0">
                                            <div class="truncate font-medium text-slate-800 dark:text-slate-100" title="{{ $item['source_name'] ?? '' }}">
                                                {{ $item['source_name'] ?? 'Unknown source' }}
                                            </div>
                                            <div class="mt-0.5 max-w-xs truncate text-xs text-slate-500 dark:text-slate-400" title="{{ $item['source_info'] ?? '' }}">
                                                {{ Str::limit((string) ($item['source_info'] ?? ''), 44) ?: 'Source information unavailable' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="border-b border-slate-100 px-5 py-3.5 dark:border-darkmode-400">
                                    <div class="max-w-xs break-all font-medium text-slate-800 dark:text-slate-100" title="{{ $item['identity'] ?? '' }}">
                                        {{ $item['identity'] ?? '-' }}
                                    </div>
                                    <div class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Primary identity</div>
                                </td>
                                <td class="border-b border-slate-100 px-5 py-3.5 dark:border-darkmode-400">
                                    @if ($hasPassword)
                                        <span class="result-status result-status--leaked">
                                            <span class="result-status-dot" aria-hidden="true"></span>
                                            Leaked
                                        </span>
                                    @else
                                        <span class="result-status result-status--safe">
                                            <span class="result-status-dot" aria-hidden="true"></span>
                                            Safe
                                        </span>
                                    @endif
                                </td>
                                <td class="border-b border-slate-100 px-5 py-3.5 dark:border-darkmode-400">
                                    @if ($visibleOtherData->isNotEmpty())
                                        <div class="flex max-w-sm flex-wrap gap-1.5">
                                            @foreach ($visibleOtherData as $key => $value)
                                                @php
                                                    $displayValue = is_scalar($value) ? (string) $value : json_encode($value);
                                                @endphp
                                                <span class="inline-block max-w-full truncate rounded bg-slate-100 px-2 py-1 text-xs text-slate-600 dark:bg-darkmode-400 dark:text-slate-300" title="{{ $key }}: {{ $displayValue }}">
                                                    {{ $key }}: {{ Str::limit($displayValue, 20) }}
                                                </span>
                                            @endforeach
                                            @if ($otherData->count() > $visibleOtherData->count())
                                                <span class="inline-flex items-center whitespace-nowrap px-1 text-xs text-slate-500 dark:text-slate-400">
                                                    +{{ $otherData->count() - $visibleOtherData->count() }} more
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-sm text-slate-400 dark:text-slate-500">-</span>
                                    @endif
                                </td>
                                <td class="border-b border-slate-100 px-5 py-3.5 text-right dark:border-darkmode-400">
                                    <div class="inline-flex items-center justify-end gap-1.5">
                                        <button type="button"
                                            class="view-detail-btn inline-flex h-9 items-center gap-1.5 rounded-md border border-slate-200 px-2.5 text-sm font-medium text-slate-600 transition-colors hover:bg-slate-50 hover:text-slate-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary dark:border-darkmode-400 dark:text-slate-300 dark:hover:bg-darkmode-500"
                                            data-tw-toggle="modal"
                                            data-tw-target="#detail-modal"
                                            data-source="{{ $item['source_name'] ?? 'Unknown source' }}"
                                            data-info="{{ $item['source_info'] ?? '' }}"
                                            data-identity="{{ $item['identity'] ?? '' }}"
                                            data-password="{{ $hasPassword ? $item['password'] : '' }}"
                                            data-has-password="{{ $hasPassword ? 'true' : 'false' }}"
                                            data-meta="{{ json_encode($item['other_data'] ?? [], JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP) }}"
                                            aria-label="View details for {{ $item['identity'] ?? 'this result' }}">
                                            <i data-lucide="file-text" class="scan-detail-icon" aria-hidden="true"></i>
                                            Details
                                        </button>

                                        @if ($hasPassword)
                                            <button type="button"
                                                class="password-toggle scan-reveal-button inline-flex h-9 items-center gap-1.5 rounded-md px-2.5 text-sm font-medium text-rose-700 transition-colors hover:bg-rose-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-500 dark:text-rose-300 dark:hover:bg-rose-900/20"
                                                data-password="{{ $item['password'] }}"
                                                aria-label="Reveal compromised password"
                                                aria-pressed="false"
                                                title="Reveal password">
                                                <i data-lucide="eye" class="scan-detail-icon" aria-hidden="true"></i>
                                                <span class="password-action-label">Reveal</span>
                                                <span class="password-value hidden break-all font-mono text-xs"></span>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center">
                                    <p class="font-medium text-slate-700 dark:text-slate-200">No results found</p>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                        There are no records attached to this scan.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                        <tr id="scan-filter-empty" class="hidden">
                            <td colspan="5" class="px-5 py-10 text-center text-sm text-slate-500 dark:text-slate-400">
                                No results match this filter.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col gap-3 border-t border-slate-200/70 px-5 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-darkmode-400">
                <p id="scan-results-count" class="text-sm text-slate-500 dark:text-slate-400" aria-live="polite">
                    Showing {{ $items->count() }} results on this page
                </p>
                @if ($items->hasPages())
                    <div>{{ $items->links('pagination::simple-tailwind') }}</div>
                @endif
            </div>
        </section>
    </div>

    <div id="detail-modal" tabindex="-1" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="detail-modal-title"
        class="modal group fixed inset-0 z-[60] flex items-start justify-center overflow-y-auto bg-slate-950/50 px-4 py-6 transition-[visibility,opacity] [&:not(.show)]:duration-[0s,0.2s] [&:not(.show)]:delay-[0.2s,0s] [&:not(.show)]:invisible [&:not(.show)]:opacity-0 [&.show]:visible [&.show]:opacity-100 [&.show]:duration-[0s,0.1s]">
        <div class="relative my-auto w-full max-w-3xl scale-95 transition-transform group-[.show]:scale-100">
            <div class="modal-content relative overflow-hidden rounded-lg bg-white dark:bg-darkmode-600">
                <div class="flex items-center justify-between border-b border-slate-200/70 px-5 py-4 dark:border-darkmode-400">
                    <h2 id="detail-modal-title" class="text-base font-semibold text-slate-800 dark:text-slate-100">
                        Result details
                    </h2>
                    <button type="button" data-tw-dismiss="modal"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-md text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary dark:text-slate-300 dark:hover:bg-darkmode-500"
                        aria-label="Close details">
                        <i data-lucide="x" class="scan-detail-icon" aria-hidden="true"></i>
                    </button>
                </div>

                <div class="grid grid-cols-1 gap-5 p-5 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="min-w-0">
                                <dt class="mb-1 text-sm text-slate-500 dark:text-slate-400">Source</dt>
                                <dd id="modal-source" class="break-words font-medium text-slate-800 dark:text-slate-100">-</dd>
                            </div>
                            <div class="min-w-0">
                                <dt class="mb-1 text-sm text-slate-500 dark:text-slate-400">Source information</dt>
                                <dd id="modal-info" class="break-words text-sm text-slate-700 dark:text-slate-300">-</dd>
                            </div>
                            <div class="min-w-0">
                                <dt class="mb-1 text-sm text-slate-500 dark:text-slate-400">Primary identity</dt>
                                <dd id="modal-identity" class="break-all font-medium text-slate-800 dark:text-slate-100">-</dd>
                            </div>
                            <div id="modal-password-field" class="min-w-0">
                                <dt class="mb-1 text-sm text-slate-500 dark:text-slate-400">Compromised secret</dt>
                                <dd class="flex min-w-0 items-start gap-2">
                                    <code id="modal-password" class="min-w-0 flex-1 break-all rounded bg-rose-50 px-2.5 py-2 text-sm text-rose-800 dark:bg-rose-900/20 dark:text-rose-300">Hidden</code>
                                    <button id="modal-password-toggle" type="button" hidden
                                        class="inline-flex min-h-9 flex-none items-center gap-1.5 rounded-md border border-slate-200 px-2.5 text-sm font-medium text-slate-600 transition-colors hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary dark:border-darkmode-400 dark:text-slate-300 dark:hover:bg-darkmode-500"
                                        aria-pressed="false">
                                        <i data-lucide="eye" class="scan-detail-icon" aria-hidden="true"></i>
                                        <span id="modal-password-action">Reveal</span>
                                    </button>
                                </dd>
                                <p id="modal-password-hint" class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                    Hidden until you choose to reveal it.
                                </p>
                            </div>
                        </dl>
                    </div>

                    <div class="md:col-span-2">
                        <div class="mb-2 flex items-center justify-between gap-3">
                            <h3 class="text-sm font-medium text-slate-700 dark:text-slate-200">Extended metadata</h3>
                            <span class="text-xs text-slate-500 dark:text-slate-400">All available fields</span>
                        </div>
                        <div id="modal-metadata-container" class="grid max-h-72 grid-cols-1 gap-2 overflow-y-auto sm:grid-cols-2" aria-live="polite">
                            <p class="text-sm text-slate-500 dark:text-slate-400">Select a result to view its metadata.</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end border-t border-slate-200/70 px-5 py-4 dark:border-darkmode-400">
                    <button type="button" data-tw-dismiss="modal"
                        class="inline-flex min-h-9 items-center justify-center rounded-md border border-slate-200 px-3 text-sm font-medium text-slate-600 transition-colors hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary dark:border-darkmode-400 dark:text-slate-300 dark:hover:bg-darkmode-500">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Filter the results currently loaded on this page.
        const filterInput = document.getElementById('scan-results-filter');
        const resultRows = Array.from(document.querySelectorAll('.result-row'));
        const filterEmptyState = document.getElementById('scan-filter-empty');
        const resultCount = document.getElementById('scan-results-count');

        if (filterInput) {
            filterInput.addEventListener('input', function () {
                const term = this.value.trim().toLocaleLowerCase();
                let visibleCount = 0;

                resultRows.forEach((row) => {
                    const matches = row.textContent.toLocaleLowerCase().includes(term);
                    row.hidden = !matches;
                    if (matches) visibleCount += 1;
                });

                if (filterEmptyState) {
                    filterEmptyState.classList.toggle('hidden', visibleCount > 0 || resultRows.length === 0);
                }

                if (resultCount) {
                    resultCount.textContent = term
                        ? `Showing ${visibleCount} of ${resultRows.length} results on this page`
                        : `Showing ${resultRows.length} results on this page`;
                }
            });
        }

        // Reveal a row's password only after an explicit click.
        document.querySelectorAll('.password-toggle').forEach((button) => {
            button.addEventListener('click', function () {
                const value = this.querySelector('.password-value');
                const label = this.querySelector('.password-action-label');
                const isVisible = !value.classList.contains('hidden');

                if (isVisible) {
                    value.textContent = '';
                    value.classList.add('hidden');
                    label.textContent = 'Reveal';
                    this.setAttribute('aria-label', 'Reveal compromised password');
                    this.setAttribute('aria-pressed', 'false');
                    this.title = 'Reveal password';
                } else {
                    value.textContent = this.dataset.password;
                    value.classList.remove('hidden');
                    label.textContent = 'Hide';
                    this.setAttribute('aria-label', 'Hide compromised password');
                    this.setAttribute('aria-pressed', 'true');
                    this.title = 'Hide password';
                }
            });
        });

        // Populate the dialog with text nodes so database values are never parsed as HTML.
        const modalSource = document.getElementById('modal-source');
        const modalInfo = document.getElementById('modal-info');
        const modalIdentity = document.getElementById('modal-identity');
        const modalPassword = document.getElementById('modal-password');
        const modalPasswordField = document.getElementById('modal-password-field');
        const modalPasswordToggle = document.getElementById('modal-password-toggle');
        const modalPasswordAction = document.getElementById('modal-password-action');
        const modalPasswordHint = document.getElementById('modal-password-hint');
        const modalMetadata = document.getElementById('modal-metadata-container');
        let activeModalPassword = '';

        document.querySelectorAll('.view-detail-btn').forEach((button) => {
            button.addEventListener('click', function () {
                modalSource.textContent = this.dataset.source || 'Unknown source';
                modalInfo.textContent = this.dataset.info || 'Source information unavailable';
                modalIdentity.textContent = this.dataset.identity || '\u2014';

                activeModalPassword = this.dataset.hasPassword === 'true' ? (this.dataset.password || '') : '';
                modalPassword.textContent = activeModalPassword ? 'Hidden' : 'No exposed password';
                modalPasswordToggle.hidden = !activeModalPassword;
                modalPasswordToggle.setAttribute('aria-pressed', 'false');
                modalPasswordAction.textContent = 'Reveal';
                modalPasswordHint.textContent = activeModalPassword
                    ? 'Hidden until you choose to reveal it.'
                    : 'This result does not include a password.';

                let metadata = {};
                try {
                    metadata = JSON.parse(this.dataset.meta || '{}');
                } catch (error) {
                    metadata = {};
                }

                modalMetadata.replaceChildren();
                const entries = Object.entries(metadata || {}).filter(([, value]) => {
                    return value !== null && value !== '' && value !== '0';
                });

                if (entries.length === 0) {
                    const emptyMessage = document.createElement('p');
                    emptyMessage.className = 'text-sm text-slate-500 dark:text-slate-400';
                    emptyMessage.textContent = 'No extended metadata available for this record.';
                    modalMetadata.appendChild(emptyMessage);
                    return;
                }

                entries.forEach(([key, value]) => {
                    const item = document.createElement('div');
                    item.className = 'min-w-0 rounded bg-slate-50 px-3 py-2 dark:bg-darkmode-700/40';

                    const label = document.createElement('span');
                    label.className = 'mb-1 block break-words text-xs text-slate-500 dark:text-slate-400';
                    label.textContent = key;

                    const content = document.createElement('span');
                    content.className = 'block break-all text-sm text-slate-800 dark:text-slate-200';
                    content.textContent = typeof value === 'object' ? JSON.stringify(value) : String(value);

                    item.append(label, content);
                    modalMetadata.appendChild(item);
                });
            });
        });

        modalPasswordToggle.addEventListener('click', function () {
            const isVisible = this.getAttribute('aria-pressed') === 'true';
            modalPassword.textContent = isVisible ? 'Hidden' : activeModalPassword;
            modalPasswordAction.textContent = isVisible ? 'Reveal' : 'Hide';
            this.setAttribute('aria-pressed', isVisible ? 'false' : 'true');
            modalPasswordHint.textContent = isVisible
                ? 'Hidden until you choose to reveal it.'
                : 'Password is visible in this dialog.';
        });
    </script>
@endpush
