<?php

namespace App\Http\Controllers;

use App\Models\AiSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AiSettingController extends Controller
{
    public function index()
    {
        return view('ai.settings.index', [
            'setting' => AiSetting::first(),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'provider' => 'required|string',
            'base_url' => 'nullable|string',
            'api_key' => 'nullable|string',
            'model' => 'required|string',
            'temperature' => 'required|numeric|min:0|max:2',
            'max_tokens' => 'required|integer|min:1|max:8192',
            'timeout' => 'required|integer|min:1|max:120',
            'is_active' => 'required|boolean',
            'cybersecurity_only' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        AiSetting::updateOrCreate(
            ['id' => 1],
            [
                'provider' => $request->provider,
                'base_url' => $request->base_url,
                'api_key' => $request->api_key,
                'model' => $request->model,
                'temperature' => $request->temperature,
                'max_tokens' => $request->max_tokens,
                'timeout' => $request->timeout,
                'is_active' => $request->is_active,
                'cybersecurity_only' => $request->cybersecurity_only,
            ]
        );

        return response()->json([
            'status' => true,
        ]);
    }
}
