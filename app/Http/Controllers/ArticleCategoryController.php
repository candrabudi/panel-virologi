<?php

namespace App\Http\Controllers;

use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ArticleCategoryController extends Controller
{
    public function index()
    {
        return view('articles.categories.index', [
            'categories' => ArticleCategory::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150|unique:article_categories,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        ArticleCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return response()->json([
            'status' => true,
        ]);
    }

    public function update(Request $request, $id)
    {
        $category = ArticleCategory::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150|unique:article_categories,name,'.$category->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return response()->json([
            'status' => true,
        ]);
    }

    public function destroy($id)
    {
        ArticleCategory::findOrFail($id)->delete();

        return response()->json([
            'status' => true,
        ]);
    }
}
