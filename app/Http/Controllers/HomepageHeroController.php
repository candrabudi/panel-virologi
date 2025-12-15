<?php

namespace App\Http\Controllers;

use App\Models\HomepageHero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomepageHeroController extends Controller
{
    public function index()
    {
        $hero = HomepageHero::first();

        return view('homepage_hero.index', compact('hero'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pre_title' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'overlay_color' => 'nullable|string|max:20',
            'overlay_opacity' => 'nullable|numeric|min:0|max:1',
            'primary_button_text' => 'nullable|string|max:255',
            'primary_button_url' => 'nullable|string|max:255',
            'secondary_button_text' => 'nullable|string|max:255',
            'secondary_button_url' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ], [
            'title.required' => 'Judul utama wajib diisi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $hero = HomepageHero::first() ?? new HomepageHero();
        $data = $validator->validated();

        $data['is_active'] = $request->boolean('is_active');

        $hero->fill($data)->save();

        return response()->json([
            'success' => true,
            'message' => 'Hero homepage berhasil disimpan',
        ]);
    }
}
