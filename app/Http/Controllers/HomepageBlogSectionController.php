<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreHomepageBlogSectionRequest;
use App\Models\HomepageBlogSection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class HomepageBlogSectionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'throttle:60,1']);
    }

    /**
     * Consistent authorization check.
     */
    private function authorizeManage(): void
    {
        $user = auth()->user();

        if ($user && ($user->role === 'admin' || (method_exists($user, 'can') && $user->can('manage-cms')))) {
            return;
        }

        Log::warning("Unauthorized attempt to manage homepage blog section by User ID: " . (auth()->id() ?? 'Guest'));
        abort(403, 'Unauthorized access to homepage CMS management');
    }

    /**
     * Display the index page (Blade).
     */
    public function index(): View
    {
        $this->authorizeManage();
        return view('homepage_blog_section.index');
    }

    /**
     * API: Get the current section data.
     */
    public function show(): JsonResponse
    {
        $this->authorizeManage();

        $section = HomepageBlogSection::first();

        if (!$section) {
            return ResponseHelper::ok(null, 'Belum ada konfigurasi section');
        }

        return ResponseHelper::ok([
            'id'        => $section->id,
            'title'     => $section->title,
            'subtitle'  => $section->subtitle,
            'is_active' => (bool) $section->is_active,
        ]);
    }

    /**
     * API: Create or Update the section.
     */
    public function store(StoreHomepageBlogSectionRequest $request): JsonResponse
    {
        // Authorization is handled by StoreHomepageBlogSectionRequest, but we keep this for consistency
        $this->authorizeManage();

        try {
            $data = $request->validated();

            $section = DB::transaction(function () use ($data) {
                $model = HomepageBlogSection::first() ?? new HomepageBlogSection();
                $model->fill($data);
                $model->save();
                return $model;
            });

            Log::info("Homepage blog section updated: ID {$section->id} by User ID " . auth()->id());

            return ResponseHelper::ok([
                'id'        => $section->id,
                'is_active' => (bool) $section->is_active,
            ], 'Section Blog & Artikel berhasil disimpan');
        } catch (\Throwable $e) {
            Log::error("Failed to save homepage blog section: " . $e->getMessage());
            return ResponseHelper::fail('Gagal menyimpan konfigurasi section', null, 500);
        }
    }
}
