<?php

namespace App\Http\Controllers;

use App\Models\HomepageHero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class HomepageHeroController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'throttle:60,1']);
    }

    private function authorizeManage(): void
    {
        if (method_exists(auth()->user(), 'can') && auth()->user()->can('manage-cms')) {
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

    /**
     * Blade CMS only.
     */
    public function index()
    {
        $this->authorizeManage();

        return view('homepage_hero.index');
    }

    /**
     * JSON – get hero data.
     */
    public function show()
    {
        $this->authorizeManage();

        $hero = HomepageHero::first();

        if (!$hero) {
            return $this->ok(null, 'No hero configured');
        }

        return $this->ok([
            'id' => $hero->id,
            'pre_title' => $hero->pre_title,
            'title' => $hero->title,
            'subtitle' => $hero->subtitle,
            'overlay_color' => $hero->overlay_color,
            'overlay_opacity' => $hero->overlay_opacity,
            'primary_button_text' => $hero->primary_button_text,
            'primary_button_url' => $hero->primary_button_url,
            'secondary_button_text' => $hero->secondary_button_text,
            'secondary_button_url' => $hero->secondary_button_url,
            'is_active' => (bool) $hero->is_active,
        ]);
    }

    /**
     * JSON – create / update hero.
     */
    public function store(Request $request)
    {
        $this->authorizeManage();

        $validator = Validator::make($request->all(), [
            'pre_title' => ['nullable', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string'],
            'overlay_color' => ['nullable', 'string', 'max:20'],
            'overlay_opacity' => ['nullable', 'numeric', 'min:0', 'max:1'],
            'primary_button_text' => ['nullable', 'string', 'max:255'],
            'primary_button_url' => ['nullable', 'string', 'max:255'],
            'secondary_button_text' => ['nullable', 'string', 'max:255'],
            'secondary_button_url' => ['nullable', 'string', 'max:255'],
            'is_active' => ['required', Rule::in(['0', '1'])],
        ], [
            'title.required' => 'Judul utama wajib diisi',
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        try {
            $hero = DB::transaction(function () use ($request) {
                $model = HomepageHero::first() ?? new HomepageHero();

                $model->fill([
                    'pre_title' => trim($request->pre_title),
                    'title' => trim($request->title),
                    'subtitle' => trim($request->subtitle),
                    'overlay_color' => $request->overlay_color,
                    'overlay_opacity' => $request->overlay_opacity,
                    'primary_button_text' => trim($request->primary_button_text),
                    'primary_button_url' => trim($request->primary_button_url),
                    'secondary_button_text' => trim($request->secondary_button_text),
                    'secondary_button_url' => trim($request->secondary_button_url),
                    'is_active' => (int) $request->is_active === 1,
                ]);

                $model->save();

                return $model;
            });

            return $this->ok([
                'id' => $hero->id,
                'is_active' => (bool) $hero->is_active,
            ], 'Homepage hero saved');
        } catch (\Throwable $e) {
            return $this->fail('Request failed', null, 500);
        }
    }
}
