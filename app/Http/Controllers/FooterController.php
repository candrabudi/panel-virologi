<?php

namespace App\Http\Controllers;

use App\Models\FooterContact;
use App\Models\FooterQuickLink;
use App\Models\FooterSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FooterController extends Controller
{
    public function index()
    {
        return view('footer.index', [
            'setting' => FooterSetting::first(),
            'links' => FooterQuickLink::orderBy('sort_order')->get(),
            'contacts' => FooterContact::orderBy('sort_order')->get(),
        ]);
    }

    public function saveSetting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'description' => 'nullable|string|max:500',
            'copyright_text' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ], [
            'logo.image' => 'Logo harus berupa file gambar',
            'logo.mimes' => 'Format logo harus PNG, JPG, atau SVG',
            'logo.max' => 'Ukuran logo maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $setting = FooterSetting::first() ?? new FooterSetting();
        $data = $validator->validated();

        if ($request->hasFile('logo')) {
            if ($setting->logo_path) {
                Storage::disk('public')->delete($setting->logo_path);
            }

            $data['logo_path'] = $request->file('logo')->store('footer', 'public');
        }

        $data['is_active'] = $request->boolean('is_active');

        $setting->fill($data)->save();

        return response()->json([
            'success' => true,
            'message' => 'Footer berhasil diperbarui',
            'logo_url' => $setting->logo_path ? asset('storage/'.$setting->logo_path) : null,
        ]);
    }

    public function saveQuickLink(Request $request)
    {
        FooterQuickLink::create(
            Validator::make($request->all(), [
                'label' => 'required|string|max:100',
                'url' => 'required|string|max:255',
            ])->validate() + ['is_active' => true]
        );

        return response()->json(['success' => true]);
    }

    public function deleteQuickLink($id)
    {
        FooterQuickLink::findOrFail($id)->delete();

        return response()->json(['success' => true]);
    }

    public function saveContact(Request $request)
    {
        FooterContact::create(
            Validator::make($request->all(), [
                'type' => 'required|string|max:50',
                'label' => 'nullable|string|max:100',
                'value' => 'required|string|max:255',
            ])->validate() + ['is_active' => true]
        );

        return response()->json(['success' => true]);
    }

    public function deleteContact($id)
    {
        FooterContact::findOrFail($id)->delete();

        return response()->json(['success' => true]);
    }
}
