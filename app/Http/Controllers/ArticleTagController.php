<?php

namespace App\Http\Controllers;

use App\Models\ArticleTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ArticleTagController extends Controller
{
    public function index()
    {
        return view('articles.tags.index', [
            'tags' => ArticleTag::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150|unique:article_tags,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        ArticleTag::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return response()->json([
            'status' => true,
        ]);
    }

    public function update(Request $request, $id)
    {
        $tag = ArticleTag::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150|unique:article_tags,name,'.$tag->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $tag->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return response()->json([
            'status' => true,
        ]);
    }

    public function destroy($id)
    {
        ArticleTag::findOrFail($id)->delete();

        return response()->json([
            'status' => true,
        ]);
    }
}
