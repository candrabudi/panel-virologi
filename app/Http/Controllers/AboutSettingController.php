<?php

namespace App\Http\Controllers;

use App\Models\AboutSetting;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Storage;

class AboutSettingController extends Controller
{
    public function edit()
    {
        $setting = AboutSetting::first() ?? new AboutSetting();
        return view('about_settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'hero_badge' => 'nullable|string',
            'hero_title' => 'nullable|string',
            'hero_description' => 'nullable|string',
            'hero_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'story_title' => 'nullable|string',
            'story_content' => 'nullable|string',
            'story_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'vision_title' => 'nullable|string',
            'vision_content' => 'nullable|string',
            'mission_title' => 'nullable|string',
            'mission_items' => 'nullable|array',
            'stats' => 'nullable|array',
            'core_values' => 'nullable|array',
            'team_members' => 'nullable|array',
            'seo_title' => 'nullable|string',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|array',
        ]);

        $setting = AboutSetting::first();
        
        // Handle Hero Image
        if ($request->hasFile('hero_image')) {
            if ($setting && $setting->hero_image) {
                // Try to extract relative path if it's stored as asset URL
                $oldPath = str_replace(asset('storage/'), '', $setting->hero_image);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('hero_image')->store('about', 'public');
            $data['hero_image'] = asset('storage/' . $path);
        }

        // Handle Story Image
        if ($request->hasFile('story_image')) {
            if ($setting && $setting->story_image) {
                $oldPath = str_replace(asset('storage/'), '', $setting->story_image);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('story_image')->store('about', 'public');
            $data['story_image'] = asset('storage/' . $path);
        }

        if ($setting) {
            $setting->update($data);
        } else {
            AboutSetting::create($data);
        }

        return ResponseHelper::ok(null, 'About settings updated successfully.');
    }
}
