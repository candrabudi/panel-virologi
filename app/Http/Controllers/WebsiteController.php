<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreWebsiteBrandingRequest;
use App\Http\Requests\StoreWebsiteContactRequest;
use App\Http\Requests\StoreWebsiteGeneralRequest;
use App\Models\Website;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\ImageManager;

class WebsiteController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'throttle:30,1']);
    }

    /**
     * Consistent authorization check.
     */
    private function authorizeManage(): void
    {
        $user = auth()->user();

        if ($user && ($user->role === 'admin' || (method_exists($user, 'can') && $user->can('manage-website')))) {
            return;
        }

        Log::warning('Unauthorized attempt to manage website settings by User ID: '.(auth()->id() ?? 'Guest'));
        abort(403, 'Unauthorized access to website management');
    }

    private function website(): Website
    {
        return Website::first() ?? new Website();
    }

    /**
     * Compress image to target size in KB.
     */
    private function compressToTargetSize(
        $image,
        string $path,
        int $targetKb = 100,
        int $startQuality = 85,
        int $minQuality = 40
    ): void {
        $quality = $startQuality;

        do {
            $image->toJpeg($quality)->save($path);
            clearstatcache(true, $path);

            $sizeKb = filesize($path) / 1024;
            $quality -= 5;
        } while ($sizeKb > $targetKb && $quality >= $minQuality);
    }

    /**
     * Display the index page (Blade).
     */
    public function index(): View
    {
        $this->authorizeManage();

        return view('website.index', [
            'website' => Website::first(),
        ]);
    }

    /**
     * API: Save general information.
     */
    public function saveGeneral(StoreWebsiteGeneralRequest $request): JsonResponse
    {
        $this->authorizeManage();

        try {
            DB::transaction(function () use ($request) {
                $website = $this->website();
                $website->fill($request->validated());
                $website->save();
            });

            Log::info('Website general information updated by User ID '.auth()->id());

            return ResponseHelper::ok(null, 'Informasi website berhasil disimpan');
        } catch (\Throwable $e) {
            Log::error('Failed to save website general info: '.$e->getMessage());

            return ResponseHelper::fail('Gagal menyimpan informasi website', null, 500);
        }
    }

    /**
     * API: Save contact information.
     */
    public function saveContact(StoreWebsiteContactRequest $request): JsonResponse
    {
        $this->authorizeManage();

        try {
            DB::transaction(function () use ($request) {
                $website = $this->website();
                $website->fill($request->validated());
                $website->save();
            });

            Log::info('Website contact information updated by User ID '.auth()->id());

            return ResponseHelper::ok(null, 'Kontak website berhasil disimpan');
        } catch (\Throwable $e) {
            Log::error('Failed to save website contact info: '.$e->getMessage());

            return ResponseHelper::fail('Gagal menyimpan kontak website', null, 500);
        }
    }

    public function saveBranding(StoreWebsiteBrandingRequest $request): JsonResponse
    {
        $this->authorizeManage();

        try {
            DB::transaction(function () use ($request) {
                $website = $this->website();
                $data = [];

                $disk = Storage::disk('public');
                $disk->makeDirectory('website');

                $manager = new ImageManager(new Driver());

                foreach (['logo_rectangle', 'logo_square', 'favicon'] as $field) {
                    if (!$request->hasFile($field)) {
                        continue;
                    }

                    if ($website->$field) {
                        $oldPath = str_replace(asset('storage/'), '', $website->$field);
                        if ($disk->exists($oldPath)) {
                            $disk->delete($oldPath);
                        }
                    }

                    $image = $manager->read(
                        $request->file($field)->getPathname()
                    );

                    $filename = $field.'_'.Str::uuid().'.png';
                    $relativePath = 'website/'.$filename;
                    $absolutePath = $disk->path($relativePath);

                    $image->save($absolutePath, new PngEncoder());

                    $data[$field] = asset('storage/'.$relativePath);
                }

                $website->fill($data);
                $website->save();
            });

            return ResponseHelper::ok(null, 'Branding website berhasil disimpan');
        } catch (\Throwable $e) {
            Log::error($e->getMessage());

            return ResponseHelper::fail('Gagal menyimpan branding website', null, 500);
        }
    }
}
