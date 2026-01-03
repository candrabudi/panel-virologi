<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreHomepageThreatMapRequest;
use App\Models\HomepageThreatMapSection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class HomepageThreatMapSectionController extends Controller
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

        Log::warning("Unauthorized attempt to manage homepage threat map by User ID: " . (auth()->id() ?? 'Guest'));
        abort(403, 'Unauthorized access to threat map CMS management');
    }

    /**
     * Display the index page (Blade).
     */
    public function index(): View
    {
        $this->authorizeManage();
        return view('homepage_threat_map.index');
    }

    /**
     * API: Get the threat map section configuration.
     */
    public function show(): JsonResponse
    {
        $this->authorizeManage();

        $section = HomepageThreatMapSection::first();

        if (!$section) {
            return ResponseHelper::ok(null, 'Belum ada konfigurasi seksi threat map');
        }

        return ResponseHelper::ok([
            'id'          => $section->id,
            'pre_title'   => $section->pre_title,
            'title'       => $section->title,
            'description' => $section->description,
            'cta_text'    => $section->cta_text,
            'cta_url'     => $section->cta_url,
            'is_active'   => (bool) $section->is_active,
            'updated_at'  => $section->updated_at,
        ]);
    }

    /**
     * API: Store or Update the threat map section.
     */
    public function store(StoreHomepageThreatMapRequest $request): JsonResponse
    {
        // Authorization is handled by StoreHomepageThreatMapRequest
        $this->authorizeManage();

        try {
            $data = $request->validated();

            $section = DB::transaction(function () use ($data) {
                $model = HomepageThreatMapSection::first() ?? new HomepageThreatMapSection();
                $model->fill($data);
                $model->save();
                return $model;
            });

            Log::info("Homepage threat map updated: ID {$section->id} by User ID " . auth()->id());

            return ResponseHelper::ok([
                'id'        => $section->id,
                'is_active' => (bool) $section->is_active,
            ], 'Section Cyber Threat Map berhasil disimpan');
        } catch (\Throwable $e) {
            Log::error("Failed to save homepage threat map: " . $e->getMessage());
            return ResponseHelper::fail('Gagal menyimpan konfigurasi threat map', null, 500);
        }
    }
}
