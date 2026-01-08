<?php

namespace App\Http\Controllers;

use App\Models\SystemTrafficLog;
use Illuminate\Http\Request;

class SystemTrafficLogController extends Controller
{
    public function index()
    {
        $logs = SystemTrafficLog::with('user')->latest()->paginate(50);
        return view('traffic_logs.index', compact('logs'));
    }

    public function show(SystemTrafficLog $log)
    {
        return response()->json($log);
    }
}
