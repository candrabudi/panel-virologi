<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class WebsiteController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'throttle:30,1']);
    }

    /* =========================
     | AUTH & RESPONSE HELPERS
     ========================= */
    private function authorizeManage(): void
    {
        if (method_exists(auth()->user(), 'can') && auth()->user()->can('manage-website')) {
            return;
        }

        if (Auth::user()->role === 'admin') {
            return;
        }

        abort(403, 'Forbidden');
    }

    private function ok($data = null, string $message = 'OK', int $code = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    private function fail(string $message = 'Request failed', $errors = null, int $code = 400)
    {
        $payload = [
            'status' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $code);
    }

    private function website(): Website
    {
        return Website::first() ?? new Website();
    }

    /* =========================
     | FILE SIZE COMPRESSION
     ========================= */
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

    /* =========================
     | PAGES
     ========================= */
    public function index()
    {
        $this->authorizeManage();

        return view('website.index', [
            'website' => Website::first(),
        ]);
    }

    /* =========================
     | GENERAL
     ========================= */
    public function saveGeneral(Request $request)
    {
        $this->authorizeManage();

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        try {
            DB::transaction(function () use ($validator) {
                $website = $this->website();
                $website->fill($validator->validated());
                $website->save();
            });

            return $this->ok(null, 'Informasi website berhasil disimpan');
        } catch (\Throwable $e) {
            report($e);

            return $this->fail('Request failed', $e->getMessage(), 500);
        }
    }

    /* =========================
     | CONTACT
     ========================= */
    public function saveContact(Request $request)
    {
        $this->authorizeManage();

        $validator = Validator::make($request->all(), [
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        try {
            DB::transaction(function () use ($validator) {
                $website = $this->website();
                $website->fill($validator->validated());
                $website->save();
            });

            return $this->ok(null, 'Kontak website berhasil disimpan');
        } catch (\Throwable $e) {
            report($e);

            return $this->fail('Request failed', $e->getMessage(), 500);
        }
    }

    /* =========================
     | BRANDING (TARGET FILE SIZE)
     ========================= */
    public function saveBranding(Request $request)
    {
        $this->authorizeManage();

        $validator = Validator::make($request->all(), [
            'logo_rectangle' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:4096'],
            'logo_square' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:4096'],
            'favicon' => ['nullable', 'image', 'mimes:png,ico', 'max:1024'],
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

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

                    if ($website->$field && $disk->exists($website->$field)) {
                        $disk->delete($website->$field);
                    }

                    $file = $request->file($field);
                    $image = $manager->read($file->getPathname());

                    $filename = $field.'_'.Str::uuid().'.jpg';
                    $absolutePath = storage_path('app/public/website/'.$filename);

                    // TARGET FILE SIZE PER FIELD
                    $targetKb = match ($field) {
                        'favicon' => 20,
                        'logo_square' => 80,
                        'logo_rectangle' => 120,
                        default => 100,
                    };

                    $this->compressToTargetSize(
                        $image,
                        $absolutePath,
                        targetKb: $targetKb,
                        startQuality: 85,
                        minQuality: 40
                    );

                    $data[$field] = asset('storage/website/'.$filename);
                }

                $website->fill($data);
                $website->save();
            });

            return $this->ok(null, 'Branding website berhasil disimpan');
        } catch (\Throwable $e) {
            report($e);

            return $this->fail('Request failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        }
    }
}
