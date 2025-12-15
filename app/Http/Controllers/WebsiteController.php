<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class WebsiteController extends Controller
{
    public function index()
    {
        $website = Website::first();

        return view('website.index', compact('website'));
    }

    private function website()
    {
        return Website::first() ?? new Website();
    }

    public function saveGeneral(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'long_description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $website = $this->website();
        $website->fill($validator->validated())->save();

        return response()->json([
            'success' => true,
            'message' => 'Informasi website berhasil disimpan',
        ]);
    }

    public function saveContact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $website = $this->website();
        $website->fill($validator->validated())->save();

        return response()->json([
            'success' => true,
            'message' => 'Kontak website berhasil disimpan',
        ]);
    }

    public function saveBranding(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'logo_rectangle' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'logo_square' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'favicon' => 'nullable|image|mimes:png,ico|max:1024',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $website = $this->website();
        $data = [];

        foreach (['logo_rectangle', 'logo_square', 'favicon'] as $field) {
            if ($request->hasFile($field)) {
                if ($website->$field) {
                    Storage::disk('public')->delete($website->$field);
                }
                $data[$field] = $request->file($field)->store('website', 'public');
            }
        }

        $website->fill($data)->save();

        return response()->json([
            'success' => true,
            'message' => 'Branding website berhasil disimpan',
        ]);
    }

    public function saveSeo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'og_image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:3072',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $website = $this->website();
        $data = $validator->validated();

        if ($request->hasFile('og_image')) {
            if ($website->og_image) {
                Storage::disk('public')->delete($website->og_image);
            }
            $data['og_image'] = $request->file('og_image')->store('website', 'public');
        }

        $website->fill($data)->save();

        return response()->json([
            'success' => true,
            'message' => 'SEO & Open Graph berhasil disimpan',
        ]);
    }
}
