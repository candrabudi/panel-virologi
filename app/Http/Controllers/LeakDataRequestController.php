<?php

namespace App\Http\Controllers;

use App\Models\LeakDataRequest;
use App\Models\LeakCheckLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeakDataRequestController extends Controller
{
    public function index()
    {
        $requests = LeakDataRequest::with(['user', 'leakCheckLog'])->latest()->simplePaginate(10);
        return view('leak_data_request.index', compact('requests'));
    }

    public function create(Request $request)
    {
        $logId = $request->query('log_id');
        $log = null;
        if ($logId) {
            $log = LeakCheckLog::find($logId);
        }
        return view('leak_data_request.create', compact('log'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'leak_check_log_id' => 'nullable|exists:leak_check_logs,id',
            'query' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone_number' => 'nullable|string|max:50',
            'department' => 'nullable|string|max:100',
            'requester_status' => 'nullable|string|max:100',
            'reason' => 'required|string',
        ]);

        $data = $validated;
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending';

        LeakDataRequest::create($data);

        return response()->json(['message' => 'Request submitted successfully.']);
    }

    // Example admin method to update status
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $leakRequest = LeakDataRequest::findOrFail($id);
        $leakRequest->update(['status' => $validated['status']]);

        return redirect()->route('leak_request.show', $id)
             ->with('success', 'Request status updated successfully to ' . ucfirst($validated['status']));
    }

    public function show(Request $request, $id)
    {
        $leakRequest = LeakDataRequest::with(['user', 'leakCheckLog'])->findOrFail($id);

        // Process Leak Data for Display (if available)
        $items = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        $search = $request->input('search');

        if ($leakRequest->leakCheckLog && $leakRequest->leakCheckLog->raw_response) {
            $allItems = collect();
            $raw = $leakRequest->leakCheckLog->raw_response;
            
            // Normalize generic array/obj to array
            if (is_string($raw)) {
                $decodedLog = json_decode($raw, true);
            } else {
                $decodedLog = $raw;
            }

            // Parsing Logic (Standardized)
            if (isset($decodedLog['List']) && is_array($decodedLog['List'])) {
                foreach ($decodedLog['List'] as $dbName => $dbData) {
                    if (isset($dbData['Data']) && is_array($dbData['Data'])) {
                        foreach ($dbData['Data'] as $item) {
                            $normalizedItem = [];
                            foreach ($item as $key => $value) {
                                $normalizedItem[strtolower($key)] = $value;
                            }

                            // Identity
                            $identity = $normalizedItem['email'] ?? 
                                       $normalizedItem['mail'] ?? 
                                       $normalizedItem['username'] ?? 
                                       $normalizedItem['user'] ?? 
                                       $normalizedItem['login'] ?? 
                                       $normalizedItem['phone'] ?? 
                                       $normalizedItem['mobile'] ?? 
                                       'UNKNOWN_IDENTITY';

                            // Password
                            $password = $normalizedItem['password'] ?? 
                                        $normalizedItem['pass'] ?? 
                                        $normalizedItem['passwd'] ?? 
                                        $normalizedItem['pwd'] ?? 
                                        $normalizedItem['hash'] ?? 
                                        null;
                            
                            // Other Data
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
            } elseif (is_array($decodedLog)) {
                 // Simple Fallback parsing
                 $dataToLoop = isset($decodedLog['data']) ? $decodedLog['data'] : $decodedLog;
                 if(is_array($dataToLoop)) {
                     foreach($dataToLoop as $k => $v) {
                         // Basic adaptation for flat arrays just to show something
                         if(is_array($v) || is_object($v)) {
                              // Deep nested not supported in fallback, would be complex
                         } else {
                             // Skip simple key-value pairs at root for now or treat as 1 item
                         }
                     }
                 }
            }

            // Search Filter
            if ($search) {
                $allItems = $allItems->filter(function ($item) use ($search) {
                    $search = strtolower($search);
                    return str_contains(strtolower($item['identity']), $search) ||
                           str_contains(strtolower($item['source_name']), $search) ||
                           str_contains(strtolower($item['source_info']), $search) ||
                           collect($item['other_data'])->contains(function($v) use ($search) {
                               return str_contains(strtolower(is_string($v) ? $v : json_encode($v)), $search);
                           });
                });
            }

            // Pagination
            $page = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
            $perPage = 10;
            $results = $allItems->slice(($page - 1) * $perPage, $perPage)->values();
            
            $items = new \Illuminate\Pagination\LengthAwarePaginator(
                $results,
                $allItems->count(),
                $perPage,
                $page,
                ['path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(), 'query' => $request->query()]
            );
        }

        return view('leak_data_request.show', compact('leakRequest', 'items', 'search'));
    }
}
