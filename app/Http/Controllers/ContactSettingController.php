<?php

namespace App\Http\Controllers;

use App\Models\ContactSetting;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Log;

class ContactSettingController extends Controller
{
    public function edit()
    {
        $setting = ContactSetting::first();
        return view('contact_settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'hero_badge' => 'nullable|string',
            'hero_title' => 'nullable|string',
            'hero_description' => 'nullable|string',
            'channels' => 'nullable|array',
            'social_title' => 'nullable|string',
            'social_description' => 'nullable|string',
            'seo_title' => 'nullable|string',
            'seo_description' => 'nullable|string|max:300',
            'seo_keywords' => 'nullable|array',
        ]);

        try {
            $setting = ContactSetting::first();
            if (!$setting) {
                $setting = new ContactSetting();
            }

            $setting->fill($request->all());
            $setting->save();

            return ResponseHelper::ok($setting, 'Contact settings updated successfully');
        } catch (\Throwable $e) {
            Log::error('Failed to update contact settings: ' . $e->getMessage());
            return ResponseHelper::fail('Failed to update contact settings: ' . $e->getMessage(), null, 500);
        }
    }
}
