<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreAiContextRequest;
use App\Models\AiContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AiContextController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'throttle:60,1']);
    }

    private function authorizeManage(): void
    {
        $user = auth()->user();
        $isAuthorized = (method_exists($user, 'can') && $user->can('manage-ai')) || $user->role === 'admin';

        if (!$isAuthorized) {
            Log::warning("Unauthorized attempt to manage AI contexts by User ID: " . $user->id);
            abort(403, 'Forbidden');
        }
    }

    public function index()
    {
        $this->authorizeManage();

        return view('ai.contexts.index');
    }

    public function list(Request $request)
    {
        $this->authorizeManage();

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
            $query->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN));
        }

        $data = $query->paginate($perPage);

        return ResponseHelper::ok($data, 'Contexts retrieved');
    }

    public function store(StoreAiContextRequest $request)
    {
        $this->authorizeManage();

        try {
            $context = DB::transaction(function () use ($request) {
                return AiContext::create([
                    'code'                => $request->code, // Sanitized in FormRequest
                    'name'                => $request->name, // Sanitized in FormRequest
                    'use_internal_source' => $request->use_internal_source,
                    'is_active'           => true,
                ]);
            });

            Log::info("AI Context created: ID {$context->id} by User ID " . auth()->id());

            return ResponseHelper::ok([
                'id'                  => $context->id,
                'code'                => $context->code,
                'name'                => $context->name,
                'use_internal_source' => (bool) $context->use_internal_source,
                'is_active'           => (bool) $context->is_active,
            ], 'Context created', 201);
        } catch (\Throwable $e) {
            Log::error("Failed to create AI context: " . $e->getMessage());
            return ResponseHelper::fail('Gagal membuat context AI.', null, 500);
        }
    }

    public function update(StoreAiContextRequest $request, $id)
    {
        $this->authorizeManage();

        if (!ctype_digit((string) $id)) {
            return ResponseHelper::fail('Invalid id', null, 400);
        }

        $context = AiContext::find($id);

        if (!$context) {
            return ResponseHelper::fail('Context not found', null, 404);
        }

        try {
            DB::transaction(function () use ($request, $context) {
                $context->update([
                    'name'                => $request->name,
                    'use_internal_source' => $request->use_internal_source,
                    'is_active'           => $request->has('is_active') ? $request->is_active : $context->is_active,
                ]);
            });

            Log::info("AI Context updated: ID {$context->id} by User ID " . auth()->id());

            return ResponseHelper::ok([
                'id'                  => $context->id,
                'code'                => $context->code,
                'name'                => $context->name,
                'use_internal_source' => (bool) $context->use_internal_source,
                'is_active'           => (bool) $context->is_active,
            ], 'Context updated');
        } catch (\Throwable $e) {
            Log::error("Failed to update AI context ID {$id}: " . $e->getMessage());
            return ResponseHelper::fail('Gagal memperbarui context AI.', null, 500);
        }
    }

    public function destroy($id)
    {
        $this->authorizeManage();

        if (!ctype_digit((string) $id)) {
            return ResponseHelper::fail('Invalid id', null, 400);
        }

        $context = AiContext::find($id);
        if (!$context) {
            return ResponseHelper::fail('Context not found', null, 404);
        }

        try {
            DB::transaction(function () use ($context) {
                $context->delete();
            });

            Log::info("AI Context deleted: ID {$id} by User ID " . auth()->id());
            return ResponseHelper::ok(null, 'Context deleted');
        } catch (\Throwable $e) {
            Log::error("Failed to delete AI context ID {$id}: " . $e->getMessage());
            return ResponseHelper::fail('Gagal menghapus context AI.', null, 500);
        }
    }
}
