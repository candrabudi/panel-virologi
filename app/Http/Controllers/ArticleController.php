<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\ArticleTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        return view('articles.index', [
            'articles' => Article::orderByDesc('id')->get(),
        ]);
    }

    public function create()
    {
        return view('articles.form', [
            'article' => null,
            'categories' => ArticleCategory::orderBy('name')->get(),
            'tags' => ArticleTag::orderBy('name')->get(),
        ]);
    }

    public function edit(Article $article)
    {
        return view('articles.form', [
            'article' => $article->load(['categories', 'tags']),
            'categories' => ArticleCategory::orderBy('name')->get(),
            'tags' => ArticleTag::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'thumbnail' => 'nullable|image|max:2048',
            'categories' => 'required|array|min:1',
            'tags' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $thumbnail = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail')->store('articles', 'public');
        }

        $article = Article::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'thumbnail' => $thumbnail,
            'seo_title' => $request->seo_title,
            'seo_description' => $request->seo_description,
            'seo_keywords' => $request->seo_keywords,
            'og_title' => $request->og_title,
            'og_description' => $request->og_description,
            'og_image' => $request->og_image,
            'is_published' => $request->is_published ? 1 : 0,
            'published_at' => $request->is_published ? now() : null,
        ]);

        $article->categories()->sync($request->categories);
        $article->tags()->sync($request->tags ?? []);

        return response()->json([
            'status' => true,
            'redirect' => '/articles',
        ]);
    }

    public function update(Request $request, Article $article)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'thumbnail' => 'nullable|image|max:2048',
            'categories' => 'required|array|min:1',
            'tags' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $thumbnail = $article->thumbnail;
        if ($request->hasFile('thumbnail')) {
            if ($thumbnail) {
                Storage::disk('public')->delete($thumbnail);
            }
            $thumbnail = $request->file('thumbnail')->store('articles', 'public');
        }

        $article->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'thumbnail' => $thumbnail,
            'seo_title' => $request->seo_title,
            'seo_description' => $request->seo_description,
            'seo_keywords' => $request->seo_keywords,
            'og_title' => $request->og_title,
            'og_description' => $request->og_description,
            'og_image' => $request->og_image,
            'is_published' => $request->is_published ? 1 : 0,
            'published_at' => $request->is_published ? now() : null,
        ]);

        $article->categories()->sync($request->categories);
        $article->tags()->sync($request->tags ?? []);

        return response()->json([
            'status' => true,
            'redirect' => '/articles',
        ]);
    }
}
