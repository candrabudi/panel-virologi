<?php

namespace App\Http\Controllers;

use App\Models\AiContext;
use App\Models\AiRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AiRuleController extends Controller
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

        return view('ai.rules.index', [
            'contexts' => AiContext::where('is_active', 1)
                ->orderBy('code')
                ->get(),
        ]);
    }

    /**
     * LIST (AJAX).
     */
    public function list(Request $request)
    {
        $this->authorizeManage();

        $validator = Validator::make($request->all(), [
            'type' => ['nullable', Rule::in([
                'allow_keyword',
                'block_topic',
                'regex',
                'source_policy',
            ])],
            'ai_context_id' => ['nullable', 'integer', 'exists:ai_contexts,id'],
            'is_active' => ['nullable', Rule::in(['0', '1'])],
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        $query = AiRule::query()
            ->with('context:id,code')
            ->where('category', 'cybersecurity')
            ->orderByDesc('id');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('ai_context_id')) {
            $query->where('ai_context_id', $request->ai_context_id);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', (int) $request->is_active === 1);
        }

        $data = $query->get()->map(function ($r) {
            return [
                'id' => $r->id,
                'type' => $r->type,
                'value' => $r->value,
                'note' => $r->note,
                'is_active' => (bool) $r->is_active,
                'context_code' => $r->context?->code,
                'created_at' => $r->created_at?->toDateTimeString(),
            ];
        });

        return $this->ok($data, 'Rules retrieved');
    }

    /**
     * STORE.
     */
    public function store(Request $request)
    {
        $this->authorizeManage();

        $validator = Validator::make($request->all(), [
            'type' => [
                'required',
                Rule::in([
                    'allow_keyword',
                    'block_topic',
                    'regex',
                    'source_policy',
                ]),
            ],
            'value' => ['required', 'string', 'max:500'],
            'ai_context_id' => ['nullable', 'integer', 'exists:ai_contexts,id'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        try {
            DB::transaction(function () use ($request) {
                AiRule::create([
                    'type' => $request->type,
                    'value' => trim($request->value),
                    'ai_context_id' => $request->ai_context_id,
                    'category' => 'cybersecurity',
                    'is_active' => true,
                    'note' => $request->note,
                ]);
            });

            return $this->ok(null, 'Rule created', 201);
        } catch (\Throwable $e) {
            return $this->fail('Request failed', null, 500);
        }
    }

    /**
     * UPDATE (value + toggle).
     */
    public function update(Request $request, $id)
    {
        $this->authorizeManage();

        if (!ctype_digit((string) $id)) {
            return $this->fail('Invalid id', null, 400);
        }

        $rule = AiRule::find($id);
        if (!$rule) {
            return $this->fail('Rule not found', null, 404);
        }

        $validator = Validator::make($request->all(), [
            'value' => ['sometimes', 'required', 'string', 'max:500'],
            'is_active' => ['sometimes', Rule::in(['0', '1'])],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        try {
            DB::transaction(function () use ($request, $rule) {
                $rule->update([
                    'value' => $request->has('value') ? trim($request->value) : $rule->value,
                    'is_active' => $request->has('is_active')
                        ? (int) $request->is_active === 1
                        : $rule->is_active,
                    'note' => $request->note ?? $rule->note,
                ]);
            });

            return $this->ok(null, 'Rule updated');
        } catch (\Throwable $e) {
            return $this->fail('Request failed', null, 500);
        }
    }

    /**
     * DELETE.
     */
    public function destroy($id)
    {
        $this->authorizeManage();

        if (!ctype_digit((string) $id)) {
            return $this->fail('Invalid id', null, 400);
        }

        $rule = AiRule::find($id);
        if (!$rule) {
            return $this->fail('Rule not found', null, 404);
        }

        try {
            DB::transaction(function () use ($rule) {
                $rule->delete();
            });

            return $this->ok(null, 'Rule deleted');
        } catch (\Throwable $e) {
            return $this->fail('Request failed', null, 500);
        }
    }
}
