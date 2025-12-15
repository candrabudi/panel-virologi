<?php

namespace App\Http\Controllers;

use App\Models\HomepageThreatMapSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomepageThreatMapSectionController extends Controller
{
    public function index()
    {
        $section = HomepageThreatMapSection::first();

        return view('homepage_threat_map.index', compact('section'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pre_title' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'cta_text' => 'nullable|string|max:255',
            'cta_url' => 'nullable|string|max:255',
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

        $section = HomepageThreatMapSection::first() ?? new HomepageThreatMapSection();

        $data = $validator->validated();
        $data['is_active'] = $request->boolean('is_active');

        $section->fill($data)->save();

        return response()->json([
            'success' => true,
            'message' => 'Section Cyber Threat Map berhasil disimpan',
        ]);
    }
}
