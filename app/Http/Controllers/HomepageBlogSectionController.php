<?php

namespace App\Http\Controllers;

use App\Models\HomepageBlogSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class HomepageBlogSectionController extends Controller
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

        return view('homepage_blog_section.index');
    }

    /**
     * JSON – get section data.
     */
    public function show()
    {
        $this->authorizeManage();

        $section = HomepageBlogSection::first();

        if (!$section) {
            return $this->ok(null, 'No section configured');
        }

        return $this->ok([
            'id' => $section->id,
            'title' => $section->title,
            'subtitle' => $section->subtitle,
            'is_active' => (bool) $section->is_active,
        ]);
    }

    /**
     * JSON – create / update section.
     */
    public function store(Request $request)
    {
        $this->authorizeManage();

        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'is_active' => ['required', Rule::in(['0', '1'])],
        ], [
            'title.required' => 'Judul section wajib diisi',
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        try {
            $section = DB::transaction(function () use ($request) {
                $model = HomepageBlogSection::first() ?? new HomepageBlogSection();

                $model->fill([
                    'title' => trim($request->title),
                    'subtitle' => trim($request->subtitle),
                    'is_active' => (int) $request->is_active === 1,
                ]);

                $model->save();

                return $model;
            });

            return $this->ok([
                'id' => $section->id,
                'is_active' => (bool) $section->is_active,
            ], 'Section Blog & Artikel berhasil disimpan');
        } catch (\Throwable $e) {
            return $this->fail('Request failed', null, 500);
        }
    }
}
