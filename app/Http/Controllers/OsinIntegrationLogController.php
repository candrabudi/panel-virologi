<?php

namespace App\Http\Controllers;

use App\Services\OsinIntegrationLogService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use LogicException;
use Throwable;
use UnexpectedValueException;

class OsinIntegrationLogController extends Controller
{
    public function index(Request $request, OsinIntegrationLogService $service)
    {
        $validated = $request->validate([
            'page' => ['nullable', 'integer', 'min:1', 'max:1000000'],
            'limit' => ['nullable', 'integer', 'in:25,50,100'],
            'date_from' => ['nullable', 'date_format:Y-m-d'],
            'date_to' => [
                'nullable',
                'date_format:Y-m-d',
                function ($attribute, $value, $fail) use ($request) {
                    $dateFrom = $request->input('date_from');
                    if ($value && $dateFrom && $value < $dateFrom) {
                        $fail('Tanggal akhir harus sama dengan atau setelah tanggal awal.');
                    }
                },
            ],
        ]);

        $page = (int) ($validated['page'] ?? 1);
        $limit = (int) ($validated['limit'] ?? 25);
        $dateFrom = $validated['date_from'] ?? null;
        $dateTo = $validated['date_to'] ?? null;
        $logs = [];
        $pagination = [
            'page' => $page,
            'limit' => $limit,
            'total' => 0,
            'totalPages' => 0,
            'hasPreviousPage' => false,
            'hasNextPage' => false,
        ];
        $analytics = [
            'total' => 0,
            'api_requests' => 0,
            'leakcheck_scans' => 0,
            'scans_with_findings' => 0,
            'leak_count' => 0,
        ];
        $chart = ['unit' => 'day', 'points' => []];
        $apiError = null;
        $apiWarnings = [];
        $fetchedAt = null;
        $displayTimezone = config('app.timezone');
        $refresh = $request->boolean('refresh');
        $requestLogs = [];
        $leakcheckLogs = [];
        $requestFeedLoaded = false;
        $leakcheckFeedLoaded = false;

        try {
            $result = $service->fetchAllLogs($refresh);
            $requestFeedLoaded = true;
            $requestLogs = collect($result['logs'])
                ->filter(fn ($row) => is_array($row))
                ->map(fn (array $row) => $this->normalizeLog($row))
                ->all();
            $fetchedAt = Carbon::parse($result['fetched_at'])->timezone($displayTimezone);
        } catch (Throwable $exception) {
            $apiWarnings[] = [
                'source' => 'API request logs',
                'message' => $this->logApiFailure('API request logs', $exception),
            ];
        }

        try {
            $result = $service->fetchAllLeakcheckResults($refresh);
            $leakcheckFeedLoaded = true;
            $leakcheckLogs = collect($result['logs'])
                ->filter(fn ($row) => is_array($row))
                ->map(fn (array $row) => $this->normalizeLeakcheckResult($row))
                ->all();
            $leakcheckFetchedAt = Carbon::parse($result['fetched_at'])->timezone($displayTimezone);
            if (!$fetchedAt || $leakcheckFetchedAt->greaterThan($fetchedAt)) {
                $fetchedAt = $leakcheckFetchedAt;
            }
        } catch (Throwable $exception) {
            $apiWarnings[] = [
                'source' => 'Leakcheck results',
                'message' => $this->logApiFailure('Leakcheck results', $exception),
            ];
        }

        if (!$requestFeedLoaded && !$leakcheckFeedLoaded) {
            $apiError = 'OSIN data sources could not be loaded.';
        } else {
            $allLogs = collect(array_merge($requestLogs, $leakcheckLogs))
                ->filter(function (array $log) use ($dateFrom, $dateTo) {
                    $date = substr((string) ($log['created_at_label'] ?? ''), 0, 10);
                    if ($date === '') {
                        return $dateFrom === null && $dateTo === null;
                    }

                    return (!$dateFrom || $date >= $dateFrom)
                        && (!$dateTo || $date <= $dateTo);
                })
                ->sortByDesc('created_at_label')
                ->values()
                ->all();

            $chart = $this->buildChart($allLogs, $dateFrom, $dateTo);
            $leakcheckRows = collect($allLogs)->where('kind', 'leakcheck');
            $analytics = [
                'total' => count($allLogs),
                'api_requests' => collect($allLogs)->where('kind', 'request')->count(),
                'leakcheck_scans' => $leakcheckRows->count(),
                'scans_with_findings' => $leakcheckRows->where('leak_count', '>', 0)->count(),
                'leak_count' => (int) $leakcheckRows->sum('leak_count'),
            ];

            $total = count($allLogs);
            $totalPages = max(1, (int) ceil($total / $limit));
            $page = min($page, $totalPages);
            $logs = array_slice($allLogs, ($page - 1) * $limit, $limit);
            $pagination = [
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
                'totalPages' => $total > 0 ? $totalPages : 0,
                'hasPreviousPage' => $page > 1,
                'hasNextPage' => $page < $totalPages && $total > 0,
            ];
        }

        return view('integrations.osin.logs', compact(
            'logs',
            'pagination',
            'analytics',
            'chart',
            'apiError',
            'apiWarnings',
            'fetchedAt',
            'displayTimezone',
            'dateFrom',
            'dateTo',
        ));
    }

    public function leakcheckDetail(OsinIntegrationLogService $service, string $id)
    {
        try {
            $result = $service->fetchLeakcheckResult($id);
        } catch (Throwable $exception) {
            $this->logApiFailure('Leakcheck result detail', $exception);

            return response()->json([
                'message' => 'Detail leakcheck tidak dapat dimuat dari OSIN saat ini.',
            ], 502);
        }

        if ($result === null) {
            return response()->json(['message' => 'Data leakcheck tidak ditemukan.'], 404);
        }

        return response()->json([
            'id' => $this->stringValue($result['id'] ?? null),
            'query' => $this->redactSensitiveQuery($this->stringValue($result['query'] ?? null)),
            'leak_count' => max(0, (int) ($result['leak_count'] ?? 0)),
            'status' => $this->stringValue($result['status'] ?? null),
            'error_message' => $this->redactSensitiveQuery($this->stringValue($result['error_message'] ?? null)),
            'raw_response' => $result['raw_response'] ?? null,
            'raw_response_source_id' => $this->stringValue($result['raw_response_source_id'] ?? null),
            'ip_address' => $this->stringValue($result['ip_address'] ?? null),
            'created_at' => $this->stringValue($result['created_at'] ?? null),
        ])->header('Cache-Control', 'private, no-store, max-age=0');
    }

    private function logApiFailure(string $source, Throwable $exception): string
    {
        if ($exception instanceof RequestException) {
            $status = $exception->response->status();
            Log::warning('OSIN API source returned an unsuccessful response.', [
                'source' => $source,
                'status' => $status,
            ]);

            return match ($status) {
                401, 403 => 'OSIN rejected the API key. Check OSIN_API_KEY.',
                429 => 'OSIN request limit reached. Try again shortly.',
                default => 'OSIN returned HTTP '.$status.'.',
            };
        }

        if ($exception instanceof ConnectionException) {
            Log::warning('OSIN API source could not be reached.', [
                'source' => $source,
                'exception' => get_class($exception),
            ]);

            return 'OSIN could not be reached. Check the network and TLS certificate configuration.';
        }

        if ($exception instanceof LogicException) {
            Log::warning('OSIN API credentials are not configured.', ['source' => $source]);

            return 'OSIN_API_KEY is not configured.';
        }

        Log::error('OSIN API source could not be processed.', [
            'source' => $source,
            'exception' => get_class($exception),
        ]);

        return 'The OSIN response could not be processed.';
    }

    private function buildChart(array $logs, ?string $dateFrom, ?string $dateTo): array
    {
        $timezone = config('app.timezone');
        $dates = collect($logs)
            ->pluck('created_at_label')
            ->filter()
            ->map(fn ($value) => substr((string) $value, 0, 10))
            ->filter(fn ($value) => preg_match('/^\d{4}-\d{2}-\d{2}$/', $value))
            ->values();

        $start = $dateFrom
            ? Carbon::parse($dateFrom, $timezone)->startOfDay()
            : ($dates->isNotEmpty() ? Carbon::parse($dates->min(), $timezone)->startOfDay() : Carbon::today($timezone)->subDays(6));
        $end = $dateTo
            ? Carbon::parse($dateTo, $timezone)->startOfDay()
            : ($dates->isNotEmpty() ? Carbon::parse($dates->max(), $timezone)->startOfDay() : Carbon::today($timezone));

        $dayCount = $start->diffInDays($end) + 1;
        $unit = $dayCount <= 45 ? 'day' : ($dayCount <= 210 ? 'week' : ($dayCount <= 900 ? 'month' : 'year'));
        $cursor = $this->alignBucketStart($start->copy(), $unit);
        $lastBucket = $this->alignBucketStart($end->copy(), $unit);
        $points = [];

        while ($cursor <= $lastBucket && count($points) < 120) {
            $key = $this->chartBucketKey($cursor, $unit);
            $points[$key] = [
                'key' => $key,
                'label' => $this->chartBucketLabel($cursor, $unit),
                'count' => 0,
            ];
            $this->advanceBucket($cursor, $unit);
        }

        foreach ($logs as $log) {
            $date = substr((string) ($log['created_at_label'] ?? ''), 0, 10);
            if ($date === '') {
                continue;
            }

            try {
                $bucket = $this->alignBucketStart(Carbon::parse($date, $timezone), $unit);
                $key = $this->chartBucketKey($bucket, $unit);
                if (isset($points[$key])) {
                    $points[$key]['count']++;
                }
            } catch (Throwable) {
                continue;
            }
        }

        return [
            'unit' => $unit,
            'unit_label' => match ($unit) {
                'day' => 'daily',
                'week' => 'weekly',
                'month' => 'monthly',
                'year' => 'yearly',
            },
            'points' => array_values($points),
            'max' => max(1, max(array_column($points, 'count') ?: [0])),
        ];
    }

    private function alignBucketStart(Carbon $date, string $unit): Carbon
    {
        return match ($unit) {
            'week' => $date->startOfWeek(),
            'month' => $date->startOfMonth(),
            'year' => $date->startOfYear(),
            default => $date->startOfDay(),
        };
    }

    private function chartBucketKey(Carbon $date, string $unit): string
    {
        return match ($unit) {
            'week' => $date->format('o-W'),
            'month' => $date->format('Y-m'),
            'year' => $date->format('Y'),
            default => $date->format('Y-m-d'),
        };
    }

    private function chartBucketLabel(Carbon $date, string $unit): string
    {
        return match ($unit) {
            'month' => $date->format('M Y'),
            'year' => $date->format('Y'),
            default => $date->format('d M'),
        };
    }

    private function advanceBucket(Carbon $date, string $unit): void
    {
        match ($unit) {
            'week' => $date->addWeek(),
            'month' => $date->addMonth(),
            'year' => $date->addYear(),
            default => $date->addDay(),
        };
    }

    private function normalizeLog(array $row): array
    {
        $query = $this->redactSensitiveQuery($this->stringValue($row['query'] ?? null));
        $mode = $this->stringValue($row['mode'] ?? null);
        $ipAddress = $this->stringValue($row['ip_address'] ?? null);
        $userAgent = $this->stringValue($row['user_agent'] ?? null);
        $location = $this->stringValue($row['location'] ?? null);
        $createdAt = $this->stringValue($row['created_at'] ?? null);

        try {
            $createdAtLabel = $createdAt === ''
                ? null
                : Carbon::parse($createdAt)->timezone(config('app.timezone'))->format('Y-m-d H:i:s');
        } catch (Throwable) {
            $createdAtLabel = $createdAt !== '' ? $createdAt : null;
        }

        $id = $this->stringValue($row['id'] ?? null);
        $searchValues = [$id, 'api request', $query, $mode, $ipAddress, $userAgent, $location, $createdAt];

        return [
            'id' => $id,
            'kind' => 'request',
            'kind_label' => 'API request',
            'query' => $query,
            'mode' => $mode,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'location' => $location,
            'status' => '',
            'leak_count' => 0,
            'error_message' => '',
            'created_at' => $createdAt,
            'created_at_label' => $createdAtLabel,
            'search_text' => Str::lower(implode(' ', array_filter($searchValues))),
        ];
    }

    private function normalizeLeakcheckResult(array $row): array
    {
        $id = $this->stringValue($row['id'] ?? null);
        $query = $this->redactSensitiveQuery($this->stringValue($row['query'] ?? null));
        $status = Str::lower($this->stringValue($row['status'] ?? null));
        $ipAddress = $this->stringValue($row['ip_address'] ?? null);
        $errorMessage = $this->redactSensitiveQuery($this->stringValue($row['error_message'] ?? null));
        $createdAt = $this->stringValue($row['created_at'] ?? null);

        try {
            $createdAtLabel = $createdAt === ''
                ? null
                : Carbon::parse($createdAt)->timezone(config('app.timezone'))->format('Y-m-d H:i:s');
        } catch (Throwable) {
            $createdAtLabel = $createdAt !== '' ? $createdAt : null;
        }

        $leakCount = max(0, (int) ($row['leak_count'] ?? 0));
        $searchValues = [$id, 'leakcheck', $query, $status, $leakCount, $ipAddress, $errorMessage, $createdAt];

        return [
            'id' => $id,
            'kind' => 'leakcheck',
            'kind_label' => 'Leakcheck',
            'query' => $query,
            'mode' => '',
            'ip_address' => $ipAddress,
            'user_agent' => '',
            'location' => '',
            'status' => $status,
            'leak_count' => $leakCount,
            'error_message' => $errorMessage,
            'created_at' => $createdAt,
            'created_at_label' => $createdAtLabel,
            'search_text' => Str::lower(implode(' ', array_filter($searchValues))),
        ];
    }

    private function stringValue(mixed $value): string
    {
        return is_scalar($value) ? trim((string) $value) : '';
    }

    private function redactSensitiveQuery(string $query): string
    {
        return preg_replace(
            '/((?:api[_-]?key|token|password|passwd|secret|authorization|cookie)\s*[=:]\s*)([^&\s,;"\']+)/i',
            '$1[REDACTED]',
            $query,
        ) ?? $query;
    }
}
