<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
            'description' => 'nullable|string|max:1000',
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
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
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

                $file = $request->file($field);
                $filename = $field.'_'.Str::uuid().'.'.$file->getClientOriginalExtension();

                $data[$field] = asset('storage/'.$file->storeAs('website', $filename, 'public'));
            }
        }

        $website->fill($data)->save();

        return response()->json([
            'success' => true,
            'message' => 'Branding website berhasil disimpan',
        ]);
    }
}
