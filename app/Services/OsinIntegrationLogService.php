<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use LogicException;
use UnexpectedValueException;

class OsinIntegrationLogService
{
    /**
     * Fetch every API page so date filters and analytics cover the full log set.
     * The upstream endpoint currently ignores date query parameters.
     *
     * @return array{logs: array<int, array>, fetched_at: string}
     */
    public function fetchAllLogs(bool $refresh = false): array
    {
        return $this->fetchAllPages(
            '/logs',
            'data',
            'osin.integration.logs.all.v1',
            $refresh,
        );
    }

    /**
     * Fetch leakcheck history while discarding raw responses, which can contain exposed credentials.
     *
     * @return array{logs: array<int, array>, fetched_at: string}
     */
    public function fetchAllLeakcheckResults(bool $refresh = false): array
    {
        return $this->fetchAllPages(
            '/leakcheck-results',
            'results',
            'osin.integration.leakcheck.summaries.v1',
            $refresh,
            static fn (array $row): array => array_intersect_key($row, array_flip([
                'id',
                'query',
                'leak_count',
                'status',
                'error_message',
                'ip_address',
                'created_at',
            ])),
        );
    }

    /**
     * Fetch one leakcheck result with its raw response on demand.
     * Raw responses are deliberately excluded from the list cache because they can contain personal data.
     */
    public function fetchLeakcheckResult(string $id): ?array
    {
        $result = $this->fetchLeakcheckResultById($id);
        if ($result === null) {
            return null;
        }

        $seenIds = [$id => true];
        $current = $result;
        $sourceId = null;

        // Cached rows can omit raw_response and point to the original scan in error_message.
        // Follow that pointer (and a short chain, if necessary) to find the original response.
        for ($depth = 0; $depth < 5 && empty($current['raw_response']); $depth++) {
            $message = is_scalar($current['error_message'] ?? null)
                ? (string) $current['error_message']
                : '';

            if (!preg_match('/source\s+log\s*#?(\d+)/i', $message, $matches)) {
                break;
            }

            $nextId = (string) $matches[1];
            if ($nextId === '' || isset($seenIds[$nextId])) {
                break;
            }

            $seenIds[$nextId] = true;
            $source = $this->fetchLeakcheckResultById($nextId);
            if ($source === null) {
                break;
            }

            $sourceId = $nextId;
            $current = $source;
        }

        if (!empty($current['raw_response'])) {
            $result['raw_response'] = $current['raw_response'];
        }

        if ($sourceId !== null) {
            $result['raw_response_source_id'] = $sourceId;
        }

        return $result;
    }

    private function fetchLeakcheckResultById(string $id): ?array
    {
        $page = 1;
        $limit = 100;
        $maxPages = 100;

        do {
            $response = $this->fetchPage('/leakcheck-results', $page, $limit);
            $response->throw();

            $payload = $response->json();
            if (
                !is_array($payload)
                || ($payload['success'] ?? false) !== true
                || !is_array($payload['results'] ?? null)
                || !is_array($payload['pagination'] ?? null)
            ) {
                throw new UnexpectedValueException('OSIN response did not match the expected format for leakcheck results.');
            }

            foreach ($payload['results'] as $row) {
                if (!is_array($row) || !isset($row['id']) || (string) $row['id'] !== $id) {
                    continue;
                }

                return array_intersect_key($row, array_flip([
                    'id',
                    'query',
                    'leak_count',
                    'status',
                    'error_message',
                    'raw_response',
                    'ip_address',
                    'created_at',
                ]));
            }

            $totalPages = max(1, (int) ($payload['pagination']['totalPages'] ?? $page));
            if ($page >= $totalPages) {
                return null;
            }

            if ($page >= $maxPages) {
                throw new UnexpectedValueException('OSIN leakcheck history is larger than the supported page limit.');
            }

            $page++;
        } while (true);
    }

    /**
     * @param  callable(array): array|null  $sanitizeRow
     * @return array{logs: array<int, array>, fetched_at: string}
     */
    private function fetchAllPages(
        string $endpoint,
        string $rowsKey,
        string $cacheKey,
        bool $refresh,
        ?callable $sanitizeRow = null,
    ): array {
        $cached = Cache::get($cacheKey);

        if (!$refresh && is_array($cached) && isset($cached['logs'], $cached['fetched_at'])) {
            return $cached;
        }

        $logs = [];
        $page = 1;
        $limit = 100;
        $maxPages = 100;

        do {
            $response = $this->fetchPage($endpoint, $page, $limit);
            $response->throw();

            $payload = $response->json();
            if (
                !is_array($payload)
                || ($payload['success'] ?? false) !== true
                || !is_array($payload[$rowsKey] ?? null)
                || !is_array($payload['pagination'] ?? null)
            ) {
                throw new UnexpectedValueException('OSIN response did not match the expected format for '.$endpoint.'.');
            }

            foreach ($payload[$rowsKey] as $index => $row) {
                if (!is_array($row)) {
                    continue;
                }

                if ($sanitizeRow !== null) {
                    $row = $sanitizeRow($row);
                }

                $id = isset($row['id']) && is_scalar($row['id']) ? (string) $row['id'] : '';
                $key = $id !== '' ? 'id:'.$id : 'page:'.$page.':row:'.$index;
                $logs[$key] = $row;
            }

            $totalPages = max(1, (int) ($payload['pagination']['totalPages'] ?? $page));
            if ($page >= $totalPages) {
                break;
            }

            if ($page >= $maxPages) {
                throw new UnexpectedValueException('OSIN log history is larger than the supported page limit.');
            }

            $page++;
        } while (true);

        $result = [
            'logs' => array_values($logs),
            'fetched_at' => now()->toIso8601String(),
        ];

        Cache::put($cacheKey, $result, now()->addMinutes(2));

        return $result;
    }

    public function fetchLogs(int $page, int $limit): Response
    {
        return $this->fetchPage('/logs', $page, $limit);
    }

    private function fetchPage(string $endpoint, int $page, int $limit): Response
    {
        $baseUrl = rtrim((string) config('services.osin.base_url'), '/');
        $apiKey = trim((string) config('services.osin.api_key'));

        if ($baseUrl === '' || $apiKey === '') {
            throw new LogicException('OSIN API credentials are not configured.');
        }

        $request = Http::acceptJson()
            ->withHeaders(['x-api-key' => $apiKey])
            ->connectTimeout(5)
            ->timeout(20);

        $caBundle = trim((string) config('services.osin.ca_bundle'));
        if ($caBundle !== '') {
            $request = $request->withOptions(['verify' => $caBundle]);
        }

        return $request->get($baseUrl . $endpoint, [
            'page' => $page,
            'limit' => $limit,
        ]);
    }
}
