<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreAboutUsRequest;
use App\Models\AboutUs;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AboutUsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'throttle:60,1']);
    }

    /**
     * Display the CMS page for About Us.
     */
    public function index(): View
    {
        $this->authorizeManage();

        $aboutPage = AboutUs::first();

        return view('about_us.cms', compact('aboutPage'));
    }

    /**
     * Return the About Us data for API consumption.
     */
    public function apiShow(): JsonResponse
    {
        $this->authorizeManage();
        
        $about = AboutUs::first();

        return ResponseHelper::ok($about);
    }

    /**
     * Store or update the About Us information.
     */
    public function store(StoreAboutUsRequest $request): JsonResponse
    {
        try {
            return DB::transaction(function () use ($request) {
                $about = AboutUs::first() ?? new AboutUs();
                
                $data = $request->validated();
                $data['is_active'] = (int) $request->is_active === 1;

                $about->fill($data);
                $about->save();

                Log::info('About Us updated by User ID: ' . auth()->id());

                return ResponseHelper::ok(null, 'Halaman Tentang Kami berhasil diperbarui');
            });
        } catch (\Throwable $e) {
            Log::error('Failed to update About Us: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);

            return ResponseHelper::fail('Gagal menyimpan data: ' . $e->getMessage(), null, 500);
        }
    }

    /**
     * Internal authorization check for administrative tasks.
     */
    private function authorizeManage(): void
    {
        $user = auth()->user();
        
        if (!$user || !((method_exists($user, 'can') && $user->can('manage-website')) || $user->role === 'admin')) {
            Log::warning('Unauthorized access attempt to AboutUsController by User ID: ' . ($user->id ?? 'Guest'));
            abort(403, 'Forbidden');
        }
    }
}
