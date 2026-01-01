<?php

namespace App\Http\Controllers;

use App\Models\HomepageHero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class HomepageHeroController extends Controller
{
    /**
     * Field yang BOLEH diterima (whitelist).
     */
    private const ALLOWED_FIELDS = [
        '_token',
        'pre_title',
        'title',
        'subtitle',
        'primary_button_text',
        'primary_button_url',
        'secondary_button_text',
        'secondary_button_url',
        'is_active',
    ];

    /**
     * Field yang WAJIB ada.
     */
    private const REQUIRED_FIELDS = [
        '_token',
        'pre_title',
        'title',
        'subtitle',
        'primary_button_text',
        'primary_button_url',
        'secondary_button_text',
        'secondary_button_url',
        'is_active',
    ];

    /**
     * ==================================================
     * INDEX (Blade CMS)
     * ==================================================.
     */
    public function index()
    {
        if (!Auth::check()) {
            abort(401);
        }

        return view('homepage_hero.index');
    }

    /**
     * ==================================================
     * SHOW (JSON – fetch current hero)
     * ==================================================.
     */
    public function show()
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        $hero = HomepageHero::latest()->first();

        if (!$hero) {
            return response()->json([
                'status' => true,
                'message' => 'No hero configured',
                'data' => null,
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => [
                'id' => $hero->id,
                'pre_title' => $hero->pre_title,
                'title' => $hero->title,
                'subtitle' => $hero->subtitle,
                'primary_button_text' => $hero->primary_button_text,
                'primary_button_url' => $hero->primary_button_url,
                'secondary_button_text' => $hero->secondary_button_text,
                'secondary_button_url' => $hero->secondary_button_url,
                'is_active' => (bool) $hero->is_active,
                'updated_at' => $hero->updated_at,
            ],
        ]);
    }

    /**
     * ==================================================
     * STORE / UPDATE (JSON – ultra secure)
     * ==================================================.
     */
    public function store(Request $request)
    {
        // =========================
        // 1. AUTH CHECK
        // =========================
        if (!Auth::check()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        // =========================
        // 2. ILLEGAL PAYLOAD CHECK
        // =========================
        $incomingKeys = array_keys($request->all());

        $illegalFields = array_diff($incomingKeys, self::ALLOWED_FIELDS);
        if (!empty($illegalFields)) {
            Log::warning('HomepageHero blocked: illegal payload', [
                'user_id' => Auth::id(),
                'ip' => $request->ip(),
                'illegal_fields' => array_values($illegalFields),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Illegal payload detected',
                'errors' => [
                    'illegal_fields' => array_values($illegalFields),
                ],
            ], 422);
        }

        $missingFields = array_diff(self::REQUIRED_FIELDS, $incomingKeys);
        if (!empty($missingFields)) {
            return response()->json([
                'status' => false,
                'message' => 'Missing required fields',
                'errors' => [
                    'missing_fields' => array_values($missingFields),
                ],
            ], 422);
        }

        // =========================
        // 3. VALIDATION
        // =========================
        try {
            $validated = $request->validate([
                'pre_title' => 'required|string|max:100',
                'title' => 'required|string|max:255',
                'subtitle' => 'required|string|max:500',
                'primary_button_text' => 'required|string|max:100',
                'primary_button_url' => 'required|string|max:255',
                'secondary_button_text' => 'required|string|max:100',
                'secondary_button_url' => 'required|string|max:255',
                'is_active' => 'required|boolean',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }

        // =========================
        // 4. SANITIZE
        // =========================
        $data = [
            'pre_title' => strip_tags(trim($validated['pre_title'])),
            'title' => strip_tags(trim($validated['title'])),
            'subtitle' => strip_tags(trim($validated['subtitle'])),
            'primary_button_text' => strip_tags(trim($validated['primary_button_text'])),
            'primary_button_url' => trim($validated['primary_button_url']),
            'secondary_button_text' => strip_tags(trim($validated['secondary_button_text'])),
            'secondary_button_url' => trim($validated['secondary_button_url']),
            'is_active' => (bool) $validated['is_active'],
        ];

        // =========================
        // 5. DB TRANSACTION
        // =========================
        DB::beginTransaction();

        try {
            if ($data['is_active']) {
                HomepageHero::where('is_active', 1)->update([
                    'is_active' => 0,
                ]);
            }

            $hero = HomepageHero::create($data);

            DB::commit();

            Log::info('HomepageHero saved', [
                'user_id' => Auth::id(),
                'hero_id' => $hero->id,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Homepage hero saved successfully',
                'data' => [
                    'id' => $hero->id,
                ],
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('HomepageHero error', [
                'user_id' => Auth::id(),
                'ip' => $request->ip(),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Internal server error',
            ], 500);
        }
    }
}
