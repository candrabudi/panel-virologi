<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\FooterSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FooterSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'throttle:60,1']);
    }

    /**
     * Show the footer settings page.
     */
    public function index(): View
    {
        $setting = FooterSetting::first();
        if (!$setting) {
             // Fallback if seeded data is missing, though migration/seed should handle it.
            $setting = new FooterSetting();
        }
        return view('footer_settings.edit', compact('setting'));
    }

    /**
     * Update the footer settings.
     */
    public function update(Request $request): JsonResponse
    {
        $setting = FooterSetting::first() ?? new FooterSetting();

        $validated = $request->validate([
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'copyright_text' => 'nullable|string',
            
            'social_links' => 'nullable|array',
            'column_1_title' => 'nullable|string',
            'column_1_links' => 'nullable|array',
            
            'column_2_title' => 'nullable|string',
            'column_2_links' => 'nullable|array',
            
            'column_3_title' => 'nullable|string',
            'column_3_links' => 'nullable|array',
        ]);

        try {
            $setting->fill($validated);
            $setting->save();
            return ResponseHelper::ok($setting, 'Footer settings updated successfully');
        } catch (\Exception $e) {
            return ResponseHelper::fail('Failed to update footer settings', $e->getMessage());
        }
    }
}
