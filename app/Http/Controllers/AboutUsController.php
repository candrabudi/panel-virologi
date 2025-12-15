<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AboutUsController extends Controller
{
    public function index()
    {
        $about = AboutUs::first();

        return view('about_us.cms', compact('about'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'breadcrumb_pre' => 'nullable|string|max:100',
            'breadcrumb_bg' => 'nullable|string|max:100',
            'page_title' => 'nullable|string|max:150',
            'headline' => 'nullable|string|max:255',
            'left_content' => 'nullable|string',
            'right_content' => 'nullable|string',
            'topics' => 'nullable|array',
            'topics.*' => 'string|max:255',
            'manifesto' => 'nullable|array',
            'manifesto.*' => 'string|max:255',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:300',
            'seo_keywords' => 'nullable|string|max:500',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:300',
            'canonical_url' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $about = AboutUs::first() ?? new AboutUs();
        $about->fill($validator->validated());
        $about->save();

        return response()->json([
            'success' => true,
            'message' => 'Tentang Kami berhasil disimpan',
        ]);
    }

    public function show()
    {
        $about = AboutUs::where('is_active', true)->firstOrFail();

        return view('about_us.frontend', compact('about'));
    }
}
