<?php

namespace App\Http\Controllers;

use App\Models\AiPromptTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AiPromptTemplateController extends Controller
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

    public function index()
    {
        $this->authorizeManage();

        return view('ai.prompts.index');
    }

    public function list(Request $request)
    {
        $this->authorizeManage();

        $validator = Validator::make($request->all(), [
            'type' => ['nullable', Rule::in(['system', 'context', 'fallback'])],
            'is_active' => ['nullable', Rule::in(['0', '1'])],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:200'],
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        $perPage = (int) $request->get('per_page', 100);

        $query = AiPromptTemplate::query()
            ->select('id', 'type', 'content', 'is_active', 'created_at')
            ->orderBy('type')
            ->orderBy('id');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', (int) $request->is_active === 1);
        }

        return $this->ok(
            $query->paginate($perPage),
            'Prompt templates retrieved'
        );
    }

    public function store(Request $request)
    {
        $this->authorizeManage();

        $validator = Validator::make($request->all(), [
            'type' => ['required', Rule::in(['system', 'context', 'fallback'])],
            'content' => ['required', 'string', 'min:10'],
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        try {
            $prompt = DB::transaction(function () use ($request) {
                return AiPromptTemplate::create([
                    'type' => $request->type,
                    'content' => trim($request->content),
                    'is_active' => true,
                ]);
            });

            return $this->ok([
                'id' => $prompt->id,
                'type' => $prompt->type,
                'is_active' => (bool) $prompt->is_active,
            ], 'Prompt created', 201);
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

        $prompt = AiPromptTemplate::find($id);

        if (!$prompt) {
            return $this->fail('Prompt not found', null, 404);
        }

        $validator = Validator::make($request->all(), [
            'content' => ['required', 'string', 'min:10'],
            'is_active' => ['required', Rule::in(['0', '1'])],
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        try {
            DB::transaction(function () use ($request, $prompt) {
                $prompt->update([
                    'content' => trim($request->content),
                    'is_active' => (int) $request->is_active === 1,
                ]);
            });

            return $this->ok([
                'id' => $prompt->id,
                'type' => $prompt->type,
                'is_active' => (bool) $prompt->is_active,
            ], 'Prompt updated');
        } catch (\Throwable $e) {
            return $this->fail('Request failed', null, 500);
        }
    }

    public function destroy($id)
    {
        $this->authorizeManage();

        if (!ctype_digit((string) $id)) {
            return $this->fail('Invalid id', null, 400);
        }

        $prompt = AiPromptTemplate::find($id);
        if (!$prompt) {
            return $this->fail('Prompt not found', null, 404);
        }

        $prompt->delete();

        return $this->ok(null, 'Prompt deleted');
    }

    public function toggle(Request $request, $id)
    {
        $this->authorizeManage();

        if (!ctype_digit((string) $id)) {
            return $this->fail('Invalid id', null, 400);
        }

        $prompt = AiPromptTemplate::find($id);
        if (!$prompt) {
            return $this->fail('Prompt not found', null, 404);
        }

        $validator = Validator::make($request->all(), [
            'is_active' => ['required', Rule::in(['0', '1'])],
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        $prompt->update([
            'is_active' => (int) $request->is_active === 1,
        ]);

        return $this->ok(null, 'Status updated');
    }
}
