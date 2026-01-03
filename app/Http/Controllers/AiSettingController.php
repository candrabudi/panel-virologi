<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreAiSettingRequest;
use App\Models\AiSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AiSettingController extends Controller
{
    public function __construct()
    {
        // Auth is required, with a tighter throttle for sensitive settings
        $this->middleware(['auth', 'throttle:30,1']);
    }

    /**
     * Authorization check for AI management.
     */
    private function authorizeManage(): void
    {
        $user = auth()->user();

        if ($user && ($user->role === 'admin' || (method_exists($user, 'can') && $user->can('manage-ai')))) {
            return;
        }

        Log::warning("Unauthorized attempt to access AI settings by User ID: " . (auth()->id() ?? 'Guest'));
        abort(403, 'Unauthorized access to AI settings');
    }

    /**
     * Display the AI settings page.
     */
    public function index(): View
    {
        $this->authorizeManage();

        $setting = AiSetting::select(
            'id',
            'provider',
            'base_url',
            'model',
            'temperature',
            'max_tokens',
            'timeout',
            'is_active',
            'cybersecurity_only'
        )->first();

        return view('ai.settings.index', [
            'setting' => $setting,
        ]);
    }

    /**
     * Update or create the singleton AI setting.
     */
    public function store(StoreAiSettingRequest $request): JsonResponse
    {
        try {
            DB::transaction(function () use ($request) {
                $setting = AiSetting::first();
                
                $data = [
                    'provider'           => $request->provider,
                    'base_url'           => $request->base_url,
                    'model'              => $request->model,
                    'temperature'        => $request->temperature,
                    'max_tokens'         => $request->max_tokens,
                    'timeout'            => $request->timeout,
                    'is_active'          => filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN),
                    'cybersecurity_only' => filter_var($request->cybersecurity_only, FILTER_VALIDATE_BOOLEAN),
                ];

                // Only update API key if provided to prevent accidental deletion
                if ($request->filled('api_key')) {
                    $data['api_key'] = $request->api_key;
                    Log::info("AI API Key updated by User ID: " . auth()->id());
                }

                AiSetting::updateOrCreate(['id' => 1], $data);
            });

            Log::info("AI settings updated by User ID: " . auth()->id());

            return ResponseHelper::ok(null, 'Konfigurasi AI berhasil disimpan');
        } catch (\Throwable $e) {
            Log::error("Failed to save AI settings: " . $e->getMessage());
            return ResponseHelper::fail('Gagal menyimpan konfigurasi AI', null, 500);
        }
    }
}
