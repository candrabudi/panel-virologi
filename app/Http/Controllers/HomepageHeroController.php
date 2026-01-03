<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreHomepageHeroRequest;
use App\Models\HomepageHero;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class HomepageHeroController extends Controller
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

        Log::warning("Unauthorized attempt to manage homepage hero by User ID: " . (auth()->id() ?? 'Guest'));
        abort(403, 'Unauthorized access to homepage CMS management');
    }

    /**
     * Display the index page (Blade).
     */
    public function index(): View
    {
        $this->authorizeManage();
        return view('homepage_hero.index');
    }

    /**
     * API: Get the current active hero or latest one.
     */
    public function show(): JsonResponse
    {
        $this->authorizeManage();

        $hero = HomepageHero::where('is_active', 1)->latest()->first() 
                ?? HomepageHero::latest()->first();

        if (!$hero) {
            return ResponseHelper::ok(null, 'Belum ada konfigurasi hero');
        }

        return ResponseHelper::ok([
            'id'                    => $hero->id,
            'pre_title'             => $hero->pre_title,
            'title'                 => $hero->title,
            'subtitle'              => $hero->subtitle,
            'primary_button_text'   => $hero->primary_button_text,
            'primary_button_url'    => $hero->primary_button_url,
            'secondary_button_text' => $hero->secondary_button_text,
            'secondary_button_url'  => $hero->secondary_button_url,
            'is_active'             => (bool) $hero->is_active,
            'updated_at'            => $hero->updated_at,
        ]);
    }

    /**
     * API: Store a new hero configuration.
     * Note: This implementation follows the previous behavior of creating a new record 
     * and optionally deactivating others if the new one is active.
     */
    public function store(StoreHomepageHeroRequest $request): JsonResponse
    {
        // Authorization is handled by StoreHomepageHeroRequest
        $this->authorizeManage();

        try {
            $data = $request->validated();

            $hero = DB::transaction(function () use ($data) {
                if ($data['is_active']) {
                    HomepageHero::where('is_active', 1)->update(['is_active' => 0]);
                }

                return HomepageHero::create($data);
            });

            Log::info("Homepage hero updated: ID {$hero->id} by User ID " . auth()->id());

            return ResponseHelper::ok([
                'id' => $hero->id,
            ], 'Homepage hero berhasil disimpan', 201);
        } catch (\Throwable $e) {
            Log::error("Failed to save homepage hero: " . $e->getMessage());
            return ResponseHelper::fail('Gagal menyimpan konfigurasi hero', null, 500);
        }
    }
}
