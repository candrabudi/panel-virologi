<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreArticleTagRequest;
use App\Models\ArticleTag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleTagController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'throttle:120,1']);
    }

    /**
     * Otorisasi dasar untuk mengelola tag.
     */
    private function authorizeManage(): void
    {
        $user = auth()->user();

        if ($user && ($user->role === 'admin' || (method_exists($user, 'can') && $user->can('manage-article')))) {
            return;
        }

        abort(403, 'Unauthorized access to tag management');
    }

    /**
     * Tampilan utama manajemen tag (CMS).
     */
    public function index(): View
    {
        $this->authorizeManage();
        return view('articles.tags.index');
    }

    /**
     * List tag dengan paginasi (API).
     */
    public function list(Request $request): JsonResponse
    {
        $this->authorizeManage();

        $query = ArticleTag::query()->orderBy('name');

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        $perPage = (int) $request->get('per_page', 10);
        return ResponseHelper::ok($query->paginate($perPage, ['id', 'name', 'slug']));
    }

    /**
     * Simpan tag baru.
     */
    public function store(StoreArticleTagRequest $request): JsonResponse
    {
        $this->authorizeManage();

        try {
            $tag = DB::transaction(function () use ($request) {
                return ArticleTag::create([
                    'name' => trim($request->name),
                    'slug' => Str::slug($request->name),
                ]);
            });

            Log::info("Tag created: ID {$tag->id} by User ID " . auth()->id());

            return ResponseHelper::ok([
                'id' => $tag->id,
                'name' => $tag->name,
                'slug' => $tag->slug,
            ], 'Tag berhasil dibuat', 201);
        } catch (\Throwable $e) {
            Log::error("Failed to create tag: " . $e->getMessage());
            return ResponseHelper::fail('Gagal membuat tag', null, 500);
        }
    }

    /**
     * Perbarui tag yang ada.
     */
    public function update(StoreArticleTagRequest $request, $id): JsonResponse
    {
        $this->authorizeManage();

        $tag = ArticleTag::find($id);
        if (!$tag) {
            return ResponseHelper::fail('Tag tidak ditemukan', null, 404);
        }

        try {
            DB::transaction(function () use ($request, $tag) {
                $tag->update([
                    'name' => trim($request->name),
                    'slug' => Str::slug($request->name),
                ]);
            });

            Log::info("Tag updated: ID {$tag->id} by User ID " . auth()->id());

            return ResponseHelper::ok([
                'id' => $tag->id,
                'name' => $tag->name,
                'slug' => $tag->slug,
            ], 'Tag berhasil diperbarui');
        } catch (\Throwable $e) {
            Log::error("Failed to update tag ID {$id}: " . $e->getMessage());
            return ResponseHelper::fail('Gagal memperbarui tag', null, 500);
        }
    }

    /**
     * Hapus tag.
     */
    public function destroy($id): JsonResponse
    {
        $this->authorizeManage();

        $tag = ArticleTag::find($id);
        if (!$tag) {
            return ResponseHelper::fail('Tag tidak ditemukan', null, 404);
        }

        try {
            $tagId = $tag->id;
            DB::transaction(fn () => $tag->delete());

            Log::info("Tag deleted: ID {$tagId} by User ID " . auth()->id());

            return ResponseHelper::ok(null, 'Tag berhasil dihapus');
        } catch (\Throwable $e) {
            Log::error("Failed to delete tag ID {$id}: " . $e->getMessage());
            return ResponseHelper::fail('Gagal menghapus tag', null, 500);
        }
    }
}
