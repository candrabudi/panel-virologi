<?php

namespace App\Http\Controllers;

use App\Models\HomepageBlogSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomepageBlogSectionController extends Controller
{
    public function index()
    {
        $section = HomepageBlogSection::first();

        return view('homepage_blog_section.index', compact('section'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
        ], [
            'title.required' => 'Judul section wajib diisi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $section = HomepageBlogSection::first() ?? new HomepageBlogSection();

        $data = $validator->validated();
        $data['is_active'] = $request->boolean('is_active');

        $section->fill($data)->save();

        return response()->json([
            'success' => true,
            'message' => 'Section Blog & Artikel berhasil disimpan',
        ]);
    }
}
