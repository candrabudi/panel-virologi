<?php

namespace App\Http\Controllers;

use App\Models\ArticleTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ArticleTagController extends Controller
{
    public function __construct()
    {
        // Terapkan middleware otentikasi dan throttling (pembatasan laju)
        $this->middleware(['auth', 'throttle:60,1']);
    }

    // Metode otorisasi serupa dengan ArticleCategoryController
    private function authorizeManage(): void
    {
        if (method_exists(auth()->user(), 'can') && auth()->user()->can('manage-article')) {
            return;
        }

        if (Auth::user()->role === 'admin') {
            return;
        }

        abort(403, 'Forbidden');
    }

    // Helper untuk respons sukses
    private function ok($data = null, string $message = 'OK', int $code = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    // Helper untuk respons gagal
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
     * Blade only.
     */
    public function index()
    {
        $this->authorizeManage();

        // Mengembalikan view tanpa data tag, data akan dimuat via AJAX
        return view('articles.tags.index');
    }

    /**
     * JSON list.
     */
    public function list(Request $request)
    {
        $this->authorizeManage();

        $query = ArticleTag::query()->orderBy('name');

        if ($request->filled('q')) { // Menggunakan 'q' untuk query, sesuai dengan JS sebelumnya
            $query->where('name', 'like', '%'.$request->q.'%');
        }

        return $this->ok(
            $query->get(['id', 'name', 'slug'])
        );
    }

    /**
     * Store.
     */
    public function store(Request $request)
    {
        $this->authorizeManage();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150|unique:article_tags,name',
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        try {
            $tag = DB::transaction(function () use ($request) {
                return ArticleTag::create([
                    'name' => trim($request->name),
                    'slug' => Str::slug($request->name),
                ]);
            });

            return $this->ok([
                'id' => $tag->id,
                'name' => $tag->name,
                'slug' => $tag->slug,
            ], 'Tag created', 201);
        } catch (\Throwable $e) {
            return $this->fail('Failed to create tag', null, 500);
        }
    }

    /**
     * Update.
     */
    public function update(Request $request, $id)
    {
        $this->authorizeManage();

        if (!ctype_digit((string) $id)) {
            return $this->fail('Invalid id', null, 400);
        }

        $tag = ArticleTag::find($id);

        if (!$tag) {
            return $this->fail('Tag not found', null, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('article_tags', 'name')->ignore($tag->id),
            ],
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        try {
            DB::transaction(function () use ($request, $tag) {
                $tag->update([
                    'name' => trim($request->name),
                    'slug' => Str::slug($request->name),
                ]);
            });

            return $this->ok([
                'id' => $tag->id,
                'name' => $tag->name,
                'slug' => $tag->slug,
            ], 'Tag updated');
        } catch (\Throwable $e) {
            return $this->fail('Failed to update tag', null, 500);
        }
    }

    /**
     * Delete.
     */
    public function destroy($id)
    {
        $this->authorizeManage();

        if (!ctype_digit((string) $id)) {
            return $this->fail('Invalid id', null, 400);
        }

        $tag = ArticleTag::find($id);

        if (!$tag) {
            return $this->fail('Tag not found', null, 404);
        }

        try {
            DB::transaction(fn () => $tag->delete());

            return $this->ok(null, 'Tag deleted');
        } catch (\Throwable $e) {
            return $this->fail('Failed to delete tag', null, 500);
        }
    }
}
