<?php

namespace App\Http\Controllers;

use App\Models\AiContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AiContextController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'throttle:60,1']);
    }

    private function authorizeManage(): void
    {
        if (method_exists(auth()->user(), 'can') && auth()->user()->can('manage-ai')) {
            return;
        }

        if (Auth::user()->role == 'admin') {
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

    public function index()
    {
        $this->authorizeManage();

        return view('ai.contexts.index');
    }

    public function list(Request $request)
    {
        $this->authorizeManage();

        $validator = Validator::make($request->all(), [
            'q' => ['nullable', 'string', 'max:100'],
            'is_active' => ['nullable', Rule::in(['0', '1'])],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:200'],
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        $q = trim((string) $request->get('q', ''));
        $perPage = (int) $request->get('per_page', 100);

        $query = AiContext::query()
            ->select('id', 'code', 'name', 'use_internal_source', 'is_active', 'created_at', 'updated_at')
            ->orderBy('id', 'asc');

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('code', 'like', "%{$q}%")
                  ->orWhere('name', 'like', "%{$q}%");
            });
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', (int) $request->is_active === 1);
        }

        $data = $query->paginate($perPage);

        return $this->ok($data, 'Contexts retrieved');
    }

    public function store(Request $request)
    {
        $this->authorizeManage();

        $validator = Validator::make($request->all(), [
            'code' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[a-z0-9_\-]+$/i',
                'unique:ai_contexts,code',
            ],
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'use_internal_source' => ['required', Rule::in(['0', '1'])],
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        try {
            $context = DB::transaction(function () use ($request) {
                return AiContext::create([
                    'code' => strtolower(trim($request->code)),
                    'name' => trim($request->name),
                    'use_internal_source' => (int) $request->use_internal_source === 1,
                    'is_active' => true,
                ]);
            });

            return $this->ok([
                'id' => $context->id,
                'code' => $context->code,
                'name' => $context->name,
                'use_internal_source' => (bool) $context->use_internal_source,
                'is_active' => (bool) $context->is_active,
            ], 'Context created', 201);
        } catch (\Throwable $e) {
            return $this->fail('Request failed', null, 500);
        }
    }

    public function update(Request $request, $id)
    {
        $this->authorizeManage();

        if (!ctype_digit((string) $id)) {
            return $this->fail('Invalid id', null, 400);
        }

        $context = AiContext::query()
            ->select('id', 'code', 'name', 'use_internal_source', 'is_active')
            ->find($id);

        if (!$context) {
            return $this->fail('Context not found', null, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'use_internal_source' => ['required', Rule::in(['0', '1'])],
            'is_active' => ['required', Rule::in(['0', '1'])],
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        try {
            DB::transaction(function () use ($request, $context) {
                $context->update([
                    'name' => trim($request->name),
                    'use_internal_source' => (int) $request->use_internal_source === 1,
                    'is_active' => (int) $request->is_active === 1,
                ]);
            });

            return $this->ok([
                'id' => $context->id,
                'code' => $context->code,
                'name' => $context->name,
                'use_internal_source' => (bool) $context->use_internal_source,
                'is_active' => (bool) $context->is_active,
            ], 'Context updated');
        } catch (\Throwable $e) {
            return $this->fail('Request failed', null, 500);
        }
    }
}
