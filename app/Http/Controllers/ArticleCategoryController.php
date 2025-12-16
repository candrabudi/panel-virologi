<?php

namespace App\Http\Controllers;

use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ArticleCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'throttle:60,1']);
    }

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
     * Blade only.
     */
    public function index()
    {
        $this->authorizeManage();

        return view('articles.categories.index');
    }

    /**
     * JSON list.
     */
    public function list(Request $request)
    {
        $this->authorizeManage();

        $query = ArticleCategory::query()->orderBy('name');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
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
            'name' => [
                'required',
                'string',
                'max:150',
                'unique:article_categories,name',
            ],
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        try {
            $category = DB::transaction(function () use ($request) {
                return ArticleCategory::create([
                    'name' => trim($request->name),
                    'slug' => Str::slug($request->name),
                ]);
            });

            return $this->ok([
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
            ], 'Category created', 201);
        } catch (\Throwable $e) {
            return $this->fail('Failed to create category', null, 500);
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

        $category = ArticleCategory::find($id);

        if (!$category) {
            return $this->fail('Category not found', null, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('article_categories', 'name')->ignore($category->id),
            ],
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        try {
            DB::transaction(function () use ($request, $category) {
                $category->update([
                    'name' => trim($request->name),
                    'slug' => Str::slug($request->name),
                ]);
            });

            return $this->ok([
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
            ], 'Category updated');
        } catch (\Throwable $e) {
            return $this->fail('Failed to update category', null, 500);
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

        $category = ArticleCategory::find($id);

        if (!$category) {
            return $this->fail('Category not found', null, 404);
        }

        try {
            DB::transaction(fn () => $category->delete());

            return $this->ok(null, 'Category deleted');
        } catch (\Throwable $e) {
            return $this->fail('Failed to delete category', null, 500);
        }
    }
}
