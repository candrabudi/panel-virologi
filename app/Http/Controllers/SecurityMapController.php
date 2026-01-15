<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SecurityMapController extends Controller
{
    public function index()
    {
        return view('security.map');
    }

    public function getAttackData()
    {
        $attacks = \Illuminate\Support\Facades\DB::table('server_anomalies')
            ->whereNotNull('latitude')
            ->select('latitude', 'longitude', 'event_type', 'country_name', 'ip_address', 'details')
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get();

        return response()->json($attacks);
    }
}
