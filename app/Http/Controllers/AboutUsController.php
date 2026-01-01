<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AboutUsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'throttle:60,1'])
             ->except(['frontend']);
    }

    private function authorizeManage(): void
    {
        if ((method_exists(auth()->user(), 'can') && auth()->user()->can('manage-website')) || Auth::user()->role === 'admin') {
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
        $payload = ['status' => false, 'message' => $message];

        if ($errors) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $code);
    }

    public function index()
    {
        $this->authorizeManage();

        $aboutPage = AboutUs::first();

        return view('about_us.cms', compact('aboutPage'));
    }

    public function apiShow()
    {
        $this->authorizeManage();
        $about = AboutUs::first();

        return $this->ok($about);
    }

    public function store(Request $request)
    {
        $this->authorizeManage();

        $validator = Validator::make($request->all(), [
            'breadcrumb_pre' => ['nullable', 'string', 'max:100'],
            'breadcrumb_bg' => ['nullable', 'string', 'max:100'],
            'page_title' => ['nullable', 'string', 'max:150'],
            'headline' => ['nullable', 'string', 'max:255'],
            'left_content' => ['nullable', 'string'],
            'right_content' => ['nullable', 'string'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:300'],
            'seo_keywords' => ['nullable', 'string', 'max:500'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string', 'max:300'],
            'canonical_url' => ['nullable', 'string', 'max:255'],
            'is_active' => ['required', Rule::in(['0', '1'])],
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        try {
            DB::transaction(function () use ($validator) {
                $about = AboutUs::first() ?? new AboutUs();
                $about->fill($validator->validated());
                $about->is_active = (int) request('is_active') === 1;
                $about->save();
            });

            return $this->ok(null, 'Tentang Kami berhasil disimpan');
        } catch (\Throwable $e) {
            return $this->fail('Request failed', null, 500);
        }
    }
}
