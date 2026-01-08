<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'throttle:60,1']);
    }

    /**
     * View for listing pages.
     */
    public function index(): View
    {
        return view('pages.index');
    }

    /**
     * API: List all pages.
     */
    public function list(): JsonResponse
    {
        $pages = Page::orderBy('id', 'asc')->get();
        return ResponseHelper::ok($pages);
    }

    /**
     * View for editing a specific page.
     */
    public function edit(Page $page): View
    {
        return view('pages.edit', compact('page'));
    }

    /**
     * API: Update a specific page.
     */
    public function update(Request $request, Page $page): JsonResponse
    {
        $validated = $request->validate([
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string|max:255',
            'hero_description' => 'nullable|string',
            'hero_bg_image' => 'nullable|string|max:255',
            'primary_button_text' => 'nullable|string|max:255',
            'primary_button_url' => 'nullable|string|max:255',
            'secondary_button_text' => 'nullable|string|max:255',
            'secondary_button_url' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'og_image' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        try {
            $page->update($validated);
            return ResponseHelper::ok($page, 'Page updated successfully');
        } catch (\Exception $e) {
            return ResponseHelper::fail('Failed to update page', $e->getMessage());
        }
    }
}
