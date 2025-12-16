<?php

namespace App\Http\Controllers;

use App\Models\HomepageThreatMapSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class HomepageThreatMapSectionController extends Controller
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

        return view('homepage_threat_map.index');
    }

    /**
     * JSON – get threat map section.
     */
    public function show()
    {
        $this->authorizeManage();

        $section = HomepageThreatMapSection::first();

        if (!$section) {
            return $this->ok(null, 'No section configured');
        }

        return $this->ok([
            'id' => $section->id,
            'pre_title' => $section->pre_title,
            'title' => $section->title,
            'description' => $section->description,
            'cta_text' => $section->cta_text,
            'cta_url' => $section->cta_url,
            'is_active' => (bool) $section->is_active,
        ]);
    }

    /**
     * JSON – create / update threat map section.
     */
    public function store(Request $request)
    {
        $this->authorizeManage();

        $validator = Validator::make($request->all(), [
            'pre_title' => ['nullable', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'cta_text' => ['nullable', 'string', 'max:255'],
            'cta_url' => ['nullable', 'string', 'max:255'],
            'is_active' => ['required', Rule::in(['0', '1'])],
        ], [
            'title.required' => 'Judul utama wajib diisi',
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        try {
            $section = DB::transaction(function () use ($request) {
                $model = HomepageThreatMapSection::first() ?? new HomepageThreatMapSection();

                $model->fill([
                    'pre_title' => trim($request->pre_title),
                    'title' => trim($request->title),
                    'description' => trim($request->description),
                    'cta_text' => trim($request->cta_text),
                    'cta_url' => trim($request->cta_url),
                    'is_active' => (int) $request->is_active === 1,
                ]);

                $model->save();

                return $model;
            });

            return $this->ok([
                'id' => $section->id,
                'is_active' => (bool) $section->is_active,
            ], 'Section Cyber Threat Map berhasil disimpan');
        } catch (\Throwable $e) {
            return $this->fail('Request failed', null, 500);
        }
    }
}
