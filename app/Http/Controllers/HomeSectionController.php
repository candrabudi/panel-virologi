<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\HomeSection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeSectionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'throttle:60,1']);
    }

    /**
     * View for listing home sections.
     */
    public function index(): View
    {
        return view('home_sections.index');
    }

    /**
     * API: List all home sections.
     */
    public function list(): JsonResponse
    {
        $sections = HomeSection::orderBy('order', 'asc')->get();
        return ResponseHelper::ok($sections);
    }

    /**
     * View for editing a specific home section.
     */
    public function edit(HomeSection $section): View
    {
        return view('home_sections.edit', compact('section'));
    }

    /**
     * API: Update a specific home section.
     */
    public function update(Request $request, HomeSection $section): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'primary_button_text' => 'nullable|string|max:255',
            'primary_button_url' => 'nullable|string|max:255',
            'secondary_button_text' => 'nullable|string|max:255',
            'secondary_button_url' => 'nullable|string|max:255',
            'badge_text' => 'nullable|string|max:255',
            'background_image' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'settings' => 'nullable|array',
        ]);

        try {
            // Handle specific settings merge if needed, or just replace
            // For now, we'll merge settings if provided
            if ($request->has('settings')) {
                $currentSettings = $section->settings ?? [];
                $validated['settings'] = array_merge($currentSettings, $request->settings ?? []);
            }

            $section->update($validated);

            return ResponseHelper::ok($section, 'Section updated successfully');
        } catch (\Exception $e) {
            return ResponseHelper::fail('Failed to update section', $e->getMessage());
        }
    }
}
