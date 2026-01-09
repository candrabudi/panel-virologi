<?php

namespace App\Http\Controllers;

use App\Models\LeakCheckSetting;
use App\Models\LeakCheckLog;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LeakCheckController extends Controller
{
    public function index()
    {
        $setting = LeakCheckSetting::first() ?? new LeakCheckSetting();
        return view('leak_check.setting', compact('setting'));
    }

    public function updateSettings(Request $request)
    {
        $user = auth()->user();
        $data = $request->validate([
            'api_endpoint' => 'nullable|url',
            'api_token' => 'nullable|string',
            'default_limit' => 'nullable|integer|min:100|max:10000',
            'lang' => 'nullable|string|max:10',
            'bot_name' => 'nullable|string',
            'is_enabled' => 'required|boolean',
        ]);

        $setting = LeakCheckSetting::first() ?? new LeakCheckSetting();

        // Security: Only admin can change critical API configuration
        if ($user->role !== 'admin') {
            unset($data['api_token']);
            unset($data['api_endpoint']);
        }

        $setting->fill($data);
        $setting->save();

        return ResponseHelper::ok(null, 'Leak check settings updated successfully.');
    }

    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string',
        ]);

        $setting = LeakCheckSetting::first();
        if (!$setting || !$setting->is_enabled || !$setting->api_token) {
            return ResponseHelper::fail('Leak check service is currently unavailable.');
        }

        try {
            $response = Http::post($setting->api_endpoint, [
                'token' => $setting->api_token,
                'request' => $request->input('query'),
                'limit' => $setting->default_limit,
                'lang' => $setting->lang,
            ]);

            $result = $response->json();

            // Log the request
            LeakCheckLog::create([
                'user_id' => auth()->id(),
                'query' => $request->input('query'),
                'leak_count' => isset($result['NumOfResults']) ? $result['NumOfResults'] : (isset($result['List']) ? count($result['List']) : 0),
                'raw_response' => $result,
                'status' => $response->successful() ? 'success' : 'failed',
                'error_message' => $result['Error code'] ?? null,
                'ip_address' => $request->ip(),
            ]);

            if ($response->successful()) {
                if (isset($result['Error code'])) {
                    return ResponseHelper::fail('API Error: ' . $result['Error code']);
                }
                return ResponseHelper::ok($result, 'Search completed.');
            } else {
                return ResponseHelper::fail('API Error: ' . ($result['Error code'] ?? 'Unknown connection error'));
            }

        } catch (\Exception $e) {
            Log::error('LeakCheck Error: ' . $e->getMessage());
            return ResponseHelper::fail('System Error: ' . $e->getMessage());
        }
    }

    public function logs()
    {
        $logs = LeakCheckLog::with('user')->latest()->paginate(20);
        return view('leak_check.logs', compact('logs'));
    }

    private function authorizeLogAccess(LeakCheckLog $log): void
    {
        $user = auth()->user();
        if ($user->role === 'admin' || $user->id === $log->user_id) {
            return;
        }

        Log::warning("Unauthorized LeakCheck log access attempt: Log ID {$log->id} by User ID " . auth()->id());
        abort(403, 'Anda tidak memiliki akses ke log ini.');
    }

    public function showLog(Request $request, LeakCheckLog $log)
    {
        $this->authorizeLogAccess($log);

        // 1. Flatten the hierarchical JSON data into a clean collection
        $allItems = collect();
        $raw = $log->raw_response;
        // ... (rest of the code remains the same)
        if (isset($raw['List']) && is_array($raw['List'])) {
            foreach ($raw['List'] as $dbName => $dbData) {
                if (isset($dbData['Data']) && is_array($dbData['Data'])) {
                    foreach ($dbData['Data'] as $item) {
                        // Normalize keys for easier detection
                        $normalizedItem = [];
                        foreach ($item as $key => $value) {
                            $normalizedItem[strtolower($key)] = $value;
                        }

                        // Intelligent Identity Detection
                        $identity = $normalizedItem['email'] ?? 
                                   $normalizedItem['mail'] ?? 
                                   $normalizedItem['username'] ?? 
                                   $normalizedItem['user'] ?? 
                                   $normalizedItem['login'] ?? 
                                   $normalizedItem['phone'] ?? 
                                   $normalizedItem['mobile'] ?? 
                                   $normalizedItem['handphone'] ?? 
                                   'UNKNOWN_IDENTITY';

                        // Intelligent Password Detection
                        $password = $normalizedItem['password'] ?? 
                                    $normalizedItem['pass'] ?? 
                                    $normalizedItem['passwd'] ?? 
                                    $normalizedItem['pwd'] ?? 
                                    $normalizedItem['hash'] ?? 
                                    null;
                        
                        // Remove identified fields (case-insensitive) to avoid duplication
                        $commonKeys = ['email', 'mail', 'username', 'user', 'login', 'phone', 'mobile', 'handphone', 'password', 'pass', 'passwd', 'pwd', 'hash'];
                        
                        $otherData = array_filter($item, function($key) use ($commonKeys) {
                            return !in_array(strtolower($key), $commonKeys);
                        }, ARRAY_FILTER_USE_KEY);

                        $allItems->push([
                            'source_name' => $dbName,
                            'source_info' => $dbData['InfoLeak'] ?? 'No description',
                            'identity' => $identity,
                            'password' => $password,
                            'other_data' => $otherData
                        ]);
                    }
                }
            }
        }

        // 2. Manual Pagination
        $page = $request->input('page', 1);
        $perPage = 10;
        $offset = ($page * $perPage) - $perPage;
        
        $itemsForCurrentPage = $allItems->slice($offset, $perPage)->values();
        
        $items = new \Illuminate\Pagination\LengthAwarePaginator(
            $itemsForCurrentPage,
            $allItems->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('leak_check.show', compact('log', 'items'));
    }

    public function exportCsv()
    {
        $user = auth()->user();
        $filename = 'leak-audit-logs-' . date('Y-m-d-His') . '.csv';
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($user) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Initiator', 'Target Query', 'Breach Count', 'IP Address', 'Status', 'Date', 'Time']);

            // Use cursor for memory efficiency with large datasets
            $query = LeakCheckLog::with('user')->latest();
            
            // If not admin, only export own logs
            if ($user->role !== 'admin') {
                $query->where('user_id', $user->id);
            }

            $logs = $query->cursor();

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user->username ?? 'Guest',
                    $log->query,
                    $log->leak_count,
                    $log->ip_address,
                    $log->status,
                    $log->created_at->format('Y-m-d'),
                    $log->created_at->format('H:i:s')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function printLogs()
    {
        $user = auth()->user();
        $query = LeakCheckLog::with('user')->latest();
        
        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        $logs = $query->limit(500)->get();
        return view('leak_check.print', compact('logs'));
    }

    public function downloadJson(LeakCheckLog $log)
    {
        // Strictly Admin Only for raw JSON export due to sensitive data
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Aksi ini hanya untuk Administrator.');
        }

        $filename = 'leak-raw-data-' . $log->id . '-' . date('Ymd-His') . '.json';
        
        return response()->streamDownload(function () use ($log) {
            echo json_encode($log->raw_response, JSON_PRETTY_PRINT);
        }, $filename, ['Content-Type' => 'application/json']);
    }
}
