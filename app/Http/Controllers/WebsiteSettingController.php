<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\WebsiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WebsiteSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'throttle:60,1']);
    }

    public function index(): View
    {
        $setting = WebsiteSetting::first();
        return view('website_settings.index', compact('setting'));
    }

    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'site_name' => 'nullable|string|max:255',
            'site_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'site_logo_footer' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'site_favicon' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:1024',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'google_analytics_id' => 'nullable|string|max:255',
            'google_console_verification' => 'nullable|string|max:255',
            'custom_head_scripts' => 'nullable|string',
            'custom_body_scripts' => 'nullable|string',
        ]);

        try {
            $setting = WebsiteSetting::first() ?? new WebsiteSetting();
            
            $data = $request->except(['site_logo', 'site_logo_footer', 'site_favicon']);
            
            $disk = Storage::disk('public');

            foreach (['site_logo', 'site_logo_footer', 'site_favicon'] as $field) {
                if ($request->hasFile($field)) {
                    // Delete old file if exists
                    if ($setting->$field) {
                        $oldPath = str_replace(asset('storage/'), '', $setting->$field);
                        if ($disk->exists($oldPath)) {
                            $disk->delete($oldPath);
                        }
                    }

                    // Store new file
                    $file = $request->file($field);
                    $filename = $field . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('website', $filename, 'public');
                    $data[$field] = asset('storage/' . $path);
                }
            }

            $setting->fill($data);
            $setting->save();

            return ResponseHelper::ok($setting, 'Website settings updated successfully');
        } catch (\Exception $e) {
            return ResponseHelper::fail('Failed to update website settings', $e->getMessage());
        }
    }
}
