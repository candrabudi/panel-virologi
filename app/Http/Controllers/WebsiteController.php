<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class WebsiteController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'throttle:30,1']);
    }

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

        if (!is_null($errors)) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $code);
    }

    private function website(): Website
    {
        return Website::first() ?? new Website();
    }

    /**
     * PAGE.
     */
    public function index()
    {
        $this->authorizeManage();

        return view('website.index', [
            'website' => Website::first(),
        ]);
    }

    /**
     * GENERAL.
     */
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
            return $this->fail('Request failed', null, 500);
        }
    }

    /**
     * CONTACT.
     */
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
            return $this->fail('Request failed', null, 500);
        }
    }

    /**
     * BRANDING.
     */
    public function saveBranding(Request $request)
    {
        $this->authorizeManage();

        $validator = Validator::make($request->all(), [
            'logo_rectangle' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'logo_square' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'favicon' => ['nullable', 'image', 'mimes:png,ico', 'max:1024'],
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        try {
            DB::transaction(function () use ($request) {
                $website = $this->website();
                $data = [];

                foreach (['logo_rectangle', 'logo_square', 'favicon'] as $field) {
                    if (!$request->hasFile($field)) {
                        continue;
                    }

                    if ($website->$field) {
                        Storage::disk('public')->delete($website->$field);
                    }

                    $file = $request->file($field);
                    $filename = $field.'_'.Str::uuid().'.'.$file->getClientOriginalExtension();

                    $path = $file->storeAs('website', $filename, 'public');
                    $data[$field] = $path;
                }

                $website->fill($data);
                $website->save();
            });

            return $this->ok(null, 'Branding website berhasil disimpan');
        } catch (\Throwable $e) {
            return $this->fail('Request failed', null, 500);
        }
    }
}
