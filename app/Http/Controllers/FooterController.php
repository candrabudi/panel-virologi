<?php

namespace App\Http\Controllers;

use App\Models\FooterContact;
use App\Models\FooterQuickLink;
use App\Models\FooterSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FooterController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'throttle:60,1']);
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

        if ($errors) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $code);
    }

    /**
     * =========================
     * PAGE (BLADE ONLY)
     * =========================.
     */
    public function index()
    {
        $this->authorizeManage();

        return view('footer.index');
    }

    /**
     * =========================
     * SETTINGS
     * =========================.
     */
    public function setting()
    {
        $this->authorizeManage();

        $setting = FooterSetting::first();

        return $this->ok([
            'description' => $setting?->description,
            'copyright_text' => $setting?->copyright_text,
            'is_active' => (bool) $setting?->is_active,
            'logo_url' => $setting?->logo_path
                ? asset('storage/'.$setting->logo_path)
                : null,
        ]);
    }

    public function saveSetting(Request $request)
    {
        $this->authorizeManage();

        $validator = Validator::make($request->all(), [
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg', 'max:2048'],
            'description' => ['nullable', 'string', 'max:500'],
            'copyright_text' => ['nullable', 'string', 'max:255'],
            'is_active' => ['required', Rule::in(['0', '1'])],
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        try {
            DB::transaction(function () use ($request, $validator) {
                $setting = FooterSetting::first() ?? new FooterSetting();
                $data = $validator->validated();

                if ($request->hasFile('logo')) {
                    if ($setting->logo_path) {
                        Storage::disk('public')->delete($setting->logo_path);
                    }

                    $data['logo_path'] = asset($request->file('logo')
                        ->store('footer', 'public'));
                }

                $data['is_active'] = (int) $request->is_active === 1;

                $setting->fill($data)->save();
            });

            return $this->ok(null, 'Footer setting updated');
        } catch (\Throwable $e) {
            return $this->fail('Request failed', null, 500);
        }
    }

    /**
     * =========================
     * QUICK LINKS
     * =========================.
     */
    public function listQuickLinks()
    {
        $this->authorizeManage();

        return $this->ok(
            FooterQuickLink::orderBy('sort_order')->get([
                'id', 'label', 'url', 'sort_order', 'is_active',
            ])
        );
    }

    public function storeQuickLink(Request $request)
    {
        $this->authorizeManage();

        $validator = Validator::make($request->all(), [
            'label' => ['required', 'string', 'max:100'],
            'url' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        FooterQuickLink::create([
            ...$validator->validated(),
            'is_active' => true,
            'sort_order' => FooterQuickLink::max('sort_order') + 1,
        ]);

        return $this->ok(null, 'Quick link added', 201);
    }

    public function deleteQuickLink($id)
    {
        $this->authorizeManage();

        if (!ctype_digit((string) $id)) {
            return $this->fail('Invalid id', null, 400);
        }

        FooterQuickLink::findOrFail($id)->delete();

        return $this->ok(null, 'Quick link deleted');
    }

    /**
     * =========================
     * CONTACTS
     * =========================.
     */
    public function listContacts()
    {
        $this->authorizeManage();

        return $this->ok(
            FooterContact::orderBy('sort_order')->get([
                'id', 'type', 'label', 'value', 'sort_order', 'is_active',
            ])
        );
    }

    public function storeContact(Request $request)
    {
        $this->authorizeManage();

        $validator = Validator::make($request->all(), [
            'type' => ['required', 'string', 'max:50'],
            'label' => ['nullable', 'string', 'max:100'],
            'value' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        FooterContact::create([
            ...$validator->validated(),
            'is_active' => true,
            'sort_order' => FooterContact::max('sort_order') + 1,
        ]);

        return $this->ok(null, 'Contact added', 201);
    }

    public function deleteContact($id)
    {
        $this->authorizeManage();

        if (!ctype_digit((string) $id)) {
            return $this->fail('Invalid id', null, 400);
        }

        FooterContact::findOrFail($id)->delete();

        return $this->ok(null, 'Contact deleted');
    }
}
