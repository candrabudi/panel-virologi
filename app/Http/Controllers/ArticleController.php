<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreArticleRequest;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\ArticleTag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'throttle:100,1']);
    }

    public function index(): View
    {
        $articles = Article::orderByDesc('id')->get();

        return view('articles.index', compact('articles'));
    }

    public function list(Request $request): JsonResponse
    {
        $query = Article::query()
            ->with(['categories', 'tags'])
            ->orderByDesc('id');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                      ->orWhere('excerpt', 'like', "%{$q}%");
            });
        }

        if ($request->filled('is_published')) {
            $query->where('is_published', (int) $request->is_published === 1);
        }

        $perPage = (int) $request->get('per_page', 10);

        return ResponseHelper::ok($query->paginate($perPage));
    }

    public function create(): View
    {
        return view('articles.form', [
            'article' => null,
            'categories' => ArticleCategory::orderBy('name')->get(),
            'tags' => ArticleTag::orderBy('name')->get(),
        ]);
    }

    public function edit(Article $article): View
    {
        return view('articles.form', [
            'article' => $article->load(['categories', 'tags']),
            'categories' => ArticleCategory::orderBy('name')->get(),
            'tags' => ArticleTag::orderBy('name')->get(),
        ]);
    }

    public function store(StoreArticleRequest $request): JsonResponse
    {
        try {
            return DB::transaction(function () use ($request) {
                $data = $request->validated();

                if ($request->hasFile('thumbnail')) {
                    $data['thumbnail'] = asset('storage/'.$request->file('thumbnail')->store('articles', 'public'));
                }

                $data['slug'] = $this->generateUniqueSlug($data['title']);
                $data['is_published'] = (int) $request->is_published === 1;
                $data['published_at'] = $data['is_published'] ? now() : null;

                $article = Article::create($data);
                $article->categories()->sync($request->categories);
                $article->tags()->sync($request->tags ?? []);

                Log::info("Article created: ID {$article->id} by User ID ".auth()->id());

                return ResponseHelper::ok(['redirect' => '/articles'], 'Artikel berhasil disimpan');
            });
        } catch (\Throwable $e) {
            Log::error('Failed to store article: '.$e->getMessage());

            return ResponseHelper::fail('Gagal menyimpan artikel: '.$e->getMessage(), null, 500);
        }
    }

    public function update(StoreArticleRequest $request, Article $article): JsonResponse
    {
        try {
            return DB::transaction(function () use ($request, $article) {
                $data = $request->validated();

                if ($request->hasFile('thumbnail')) {
                    $oldPath = DB::table('articles')->where('id', $article->id)->value('thumbnail');
                    if ($oldPath) {
                        Storage::disk('public')->delete($oldPath);
                    }
                    $data['thumbnail'] = asset('storage/'.$request->file('thumbnail')->store('articles', 'public'));
                }

                if ($data['title'] !== $article->title) {
                    $data['slug'] = $this->generateUniqueSlug($data['title'], $article->id);
                }

                $data['is_published'] = (int) $request->is_published === 1;
                if ($data['is_published'] && !$article->is_published) {
                    $data['published_at'] = now();
                }

                $article->update($data);
                $article->categories()->sync($request->categories);
                $article->tags()->sync($request->tags ?? []);

                Log::info("Article updated: ID {$article->id} by User ID ".auth()->id());

                return ResponseHelper::ok(['redirect' => '/articles'], 'Artikel berhasil diperbarui');
            });
        } catch (\Throwable $e) {
            Log::error("Failed to update article ID {$article->id}: ".$e->getMessage());

            return ResponseHelper::fail('Gagal memperbarui artikel: '.$e->getMessage(), null, 500);
        }
    }

    public function destroy(Article $article): JsonResponse
    {
        try {
            if ($article->thumbnail) {
                Storage::disk('public')->delete($article->thumbnail);
            }

            $articleId = $article->id;
            $article->delete();

            Log::info("Article deleted: ID {$articleId} by User ID ".auth()->id());

            return ResponseHelper::ok(null, 'Artikel berhasil dihapus');
        } catch (\Throwable $e) {
            Log::error('Failed to delete article: '.$e->getMessage());

            return ResponseHelper::fail('Gagal menghapus artikel', null, 500);
        }
    }

    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {
            $path = $request->file('file')->store('articles/content', 'public');

            return response()->json([
                'location' => asset('storage/'.$path),
            ]);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Upload failed'], 500);
        }
    }

    private function generateUniqueSlug(string $title, ?int $exceptId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (Article::where('slug', $slug)->where('id', '!=', $exceptId)->exists()) {
            $slug = $originalSlug.'-'.$count++;
        }

        return $slug;
    }
}
