<?php

namespace App\Http\Controllers;

use App\Models\AiContext;
use App\Models\AiPromptBinding;
use App\Models\AiPromptTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AiPromptBindingController extends Controller
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

        return view('ai.bindings.index', [
            'contexts' => AiContext::where('is_active', 1)->orderBy('code')->get(),
            'prompts' => AiPromptTemplate::where('is_active', 1)->orderBy('type')->get(),
        ]);
    }

    public function list(Request $request)
    {
        $this->authorizeManage();

        $validator = Validator::make($request->all(), [
            'ai_context_id' => ['nullable', 'integer', 'exists:ai_contexts,id'],
            'ai_prompt_template_id' => ['nullable', 'integer', 'exists:ai_prompt_templates,id'],
            'is_active' => ['nullable', Rule::in(['0', '1'])],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:200'],
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        $perPage = (int) $request->get('per_page', 100);

        $data = AiPromptBinding::query()
            ->with([
                'context:id,code,name',
                'prompt:id,type,content',
            ])
            ->orderBy('id', 'asc')
            ->paginate($perPage);

        $data->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'is_active' => (bool) $item->is_active,
                'context' => [
                    'code' => $item->context->code,
                ],
                'prompt' => [
                    'type' => $item->prompt->type,
                    'content_preview' => Str::limit($item->prompt->content, 120),
                    'content_full' => $item->prompt->content,
                ],
            ];
        });

        return $this->ok($data, 'Bindings retrieved');
    }

    public function store(Request $request)
    {
        $this->authorizeManage();

        $validator = Validator::make($request->all(), [
            'ai_context_id' => ['required', 'integer', 'exists:ai_contexts,id'],
            'ai_prompt_template_id' => ['required', 'integer', 'exists:ai_prompt_templates,id'],
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        try {
            DB::transaction(function () use ($request) {
                AiPromptBinding::firstOrCreate(
                    [
                        'ai_context_id' => $request->ai_context_id,
                        'ai_prompt_template_id' => $request->ai_prompt_template_id,
                    ],
                    [
                        'is_active' => true,
                    ]
                );
            });

            return $this->ok(null, 'Binding saved', 201);
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

        $binding = AiPromptBinding::find($id);

        if (!$binding) {
            return $this->fail('Binding not found', null, 404);
        }

        $validator = Validator::make($request->all(), [
            'is_active' => ['required', Rule::in(['0', '1'])],
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        try {
            DB::transaction(function () use ($binding, $request) {
                $binding->update([
                    'is_active' => (int) $request->is_active === 1,
                ]);
            });

            return $this->ok(null, 'Binding updated');
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

        $binding = AiPromptBinding::find($id);

        if (!$binding) {
            return $this->fail('Binding not found', null, 404);
        }

        try {
            DB::transaction(function () use ($binding) {
                $binding->delete();
            });

            return $this->ok(null, 'Binding deleted');
        } catch (\Throwable $e) {
            return $this->fail('Request failed', null, 500);
        }
    }
}
