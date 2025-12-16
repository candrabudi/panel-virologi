<?php

namespace App\Http\Controllers;

use App\Models\AiSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AiSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'throttle:30,1']);
    }

    private function authorizeManage(): void
    {
        if (method_exists(auth()->user(), 'can') && auth()->user()->can('manage-ai')) {
            return;
        }

        if (Auth::user()->role === 'admin') {
            return;
        }

        abort(403, 'Forbidden');
    }

    private function ok($data = null, string $message = 'OK', int $code = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    private function fail(string $message = 'Request failed', $errors = null, int $code = 400)
    {
        $payload = [
            'status' => false,
            'message' => $message,
        ];

        if (!is_null($errors)) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $code);
    }

    /**
     * PAGE.
     */
    public function index()
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
     * STORE / UPDATE (singleton).
     */
    public function store(Request $request)
    {
        $this->authorizeManage();

        $validator = Validator::make($request->all(), [
            'provider' => [
                'required',
                'string',
                Rule::in(['openai', 'azure', 'custom']),
            ],
            'base_url' => ['nullable', 'string', 'max:255'],
            'api_key' => ['nullable', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:100'],
            'temperature' => ['required', 'numeric', 'min:0', 'max:2'],
            'max_tokens' => ['required', 'integer', 'min:1', 'max:8192'],
            'timeout' => ['required', 'integer', 'min:1', 'max:120'],
            'is_active' => ['required', Rule::in(['0', '1'])],
            'cybersecurity_only' => ['required', Rule::in(['0', '1'])],
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        try {
            DB::transaction(function () use ($request) {
                AiSetting::updateOrCreate(
                    ['id' => 1],
                    [
                        'provider' => $request->provider,
                        'base_url' => $request->base_url,
                        'api_key' => $request->filled('api_key')
                            ? $request->api_key
                            : AiSetting::value('api_key'),
                        'model' => $request->model,
                        'temperature' => $request->temperature,
                        'max_tokens' => $request->max_tokens,
                        'timeout' => $request->timeout,
                        'is_active' => (int) $request->is_active === 1,
                        'cybersecurity_only' => (int) $request->cybersecurity_only === 1,
                    ]
                );
            });

            return $this->ok(null, 'AI setting saved');
        } catch (\Throwable $e) {
            return $this->fail('Request failed', null, 500);
        }
    }
}
