<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreArticleCategoryRequest;
use App\Models\ArticleCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'throttle:60,1']);
    }

    /**
     * Consistent authorization check.
     */
    private function authorizeManage(): void
    {
        $user = auth()->user();

        if ($user && ($user->role === 'admin' || (method_exists($user, 'can') && $user->can('manage-article')))) {
            return;
        }

        Log::warning("Unauthorized attempt to manage article categories by User ID: " . (auth()->id() ?? 'Guest'));
        abort(403, 'Unauthorized access to article category management');
    }

    /**
     * Display the index page (Blade).
     */
    public function index(): View
    {
        $this->authorizeManage();
        return view('articles.categories.index');
    }

    /**
     * API: List categories with pagination and search.
     */
    public function list(Request $request): JsonResponse
    {
        $this->authorizeManage();

        $query = ArticleCategory::query()->orderBy('name');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $perPage = (int) $request->get('per_page', 10);
        $categories = $query->paginate($perPage, ['id', 'name', 'slug']);

        return ResponseHelper::ok($categories);
    }

    /**
     * API: Store a new category.
     */
    public function store(StoreArticleCategoryRequest $request): JsonResponse
    {
        // Authorization is handled by the FormRequest class but we keep this for consistency if called directly via other means
        $this->authorizeManage();

        try {
            $data = $request->validated();
            $data['slug'] = Str::slug($data['name']);

            $category = DB::transaction(function () use ($data) {
                return ArticleCategory::create($data);
            });

            Log::info("Article category created: ID {$category->id} ('{$category->name}') by User ID " . auth()->id());

            return ResponseHelper::ok([
                'id'   => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
            ], 'Kategori berhasil disimpan', 201);
        } catch (\Throwable $e) {
            Log::error("Failed to create article category: " . $e->getMessage());
            return ResponseHelper::fail('Gagal menyimpan kategori', null, 500);
        }
    }

    /**
     * API: Update an existing category.
     */
    public function update(StoreArticleCategoryRequest $request, $id): JsonResponse
    {
        $this->authorizeManage();

        $category = ArticleCategory::find($id);
        if (!$category) {
            return ResponseHelper::fail('Kategori tidak ditemukan', null, 404);
        }

        try {
            $data = $request->validated();
            $data['slug'] = Str::slug($data['name']);

            DB::transaction(function () use ($category, $data) {
                $category->update($data);
            });

            Log::info("Article category updated: ID {$category->id} by User ID " . auth()->id());

            return ResponseHelper::ok([
                'id'   => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
            ], 'Kategori berhasil diperbarui');
        } catch (\Throwable $e) {
            Log::error("Failed to update article category ID {$id}: " . $e->getMessage());
            return ResponseHelper::fail('Gagal memperbarui kategori', null, 500);
        }
    }

    /**
     * API: Delete a category.
     */
    public function destroy($id): JsonResponse
    {
        $this->authorizeManage();

        $category = ArticleCategory::find($id);
        if (!$category) {
            return ResponseHelper::fail('Kategori tidak ditemukan', null, 404);
        }

        try {
            $categoryId = $category->id;
            $categoryName = $category->name;
            
            DB::transaction(fn () => $category->delete());

            Log::info("Article category deleted: ID {$categoryId} ('{$categoryName}') by User ID " . auth()->id());

            return ResponseHelper::ok(null, 'Kategori berhasil dihapus');
        } catch (\Throwable $e) {
            Log::error("Failed to delete article category ID {$id}: " . $e->getMessage());
            return ResponseHelper::fail('Gagal menghapus kategori', null, 500);
        }
    }
}
