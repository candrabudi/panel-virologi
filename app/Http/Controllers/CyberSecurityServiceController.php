<?php

namespace App\Http\Controllers;

use App\Models\CyberSecurityService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CyberSecurityServiceController extends Controller
{
    protected function normalizeJson(Request $request, array $fields): array
    {
        $payload = $request->all();

        foreach ($fields as $field) {
            if (isset($payload[$field]) && is_string($payload[$field])) {
                $payload[$field] = json_decode($payload[$field], true) ?: [];
            }
        }

        return $payload;
    }

    public function index()
    {
        return view('cyber_security_services.index');
    }

    public function create()
    {
        return view('cyber_security_services.create');
    }

    public function edit(CyberSecurityService $cyberSecurityService)
    {
        return view('cyber_security_services.edit', compact('cyberSecurityService'));
    }

    public function list()
    {
        return response()->json(
            CyberSecurityService::orderByDesc('id')->get()
        );
    }

    public function store(Request $request)
    {
        $payload = $this->normalizeJson($request, [
            'service_scope',
            'deliverables',
            'target_audience',
            'ai_keywords',
            'seo_keywords',
        ]);

        $data = validator($payload, [
            'name' => 'required|string|max:255',
            'short_name' => 'nullable|string|max:255',
            'category' => 'required|in:soc,pentest,audit,incident_response,cloud_security,governance,training,consulting',

            'summary' => 'nullable|string',
            'description' => 'nullable|string',

            'service_scope' => 'nullable|array',
            'deliverables' => 'nullable|array',
            'target_audience' => 'nullable|array',

            'ai_keywords' => 'nullable|array',
            'ai_domain' => 'nullable|string',
            'is_ai_visible' => 'boolean',

            'cta_label' => 'nullable|string|max:255',
            'cta_url' => 'nullable|string|max:255',

            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:300',
            'seo_keywords' => 'nullable|array',

            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ])->validate();

        $data['slug'] = Str::slug($data['name']);

        CyberSecurityService::create($data);

        return response()->json(['success' => true]);
    }

    public function update(Request $request, CyberSecurityService $cyberSecurityService)
    {
        $payload = $this->normalizeJson($request, [
            'service_scope',
            'deliverables',
            'target_audience',
            'ai_keywords',
            'seo_keywords',
        ]);

        $data = validator($payload, [
            'name' => 'required|string|max:255',
            'short_name' => 'nullable|string|max:255',
            'category' => 'required|in:soc,pentest,audit,incident_response,cloud_security,governance,training,consulting',

            'summary' => 'nullable|string',
            'description' => 'nullable|string',

            'service_scope' => 'nullable|array',
            'deliverables' => 'nullable|array',
            'target_audience' => 'nullable|array',

            'ai_keywords' => 'nullable|array',
            'ai_domain' => 'nullable|string',
            'is_ai_visible' => 'boolean',

            'cta_label' => 'nullable|string|max:255',
            'cta_url' => 'nullable|string|max:255',

            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:300',
            'seo_keywords' => 'nullable|array',

            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ])->validate();

        $data['slug'] = Str::slug($data['name']);

        $cyberSecurityService->update($data);

        return response()->json(['success' => true]);
    }

    public function destroy(CyberSecurityService $cyberSecurityService)
    {
        $cyberSecurityService->delete();

        return response()->json(['success' => true]);
    }
}
