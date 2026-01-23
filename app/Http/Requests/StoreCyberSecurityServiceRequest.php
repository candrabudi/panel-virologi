<?php

namespace App\Http\Requests;

use App\Helpers\SecurityHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCyberSecurityServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user
            && (
                $user->role === 'admin'
                || (method_exists($user, 'can') && $user->can('manage-cyber-security'))
            );
    }

    public function rules(): array
    {
        $id = $this->route('cyberSecurityService')
            ? $this->route('cyberSecurityService')->id
            : null;

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('cyber_security_services', 'name')->ignore($id)],
            'short_name' => ['nullable', 'string', 'max:255'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'category' => ['required', 'in:soc,pentest,audit,incident_response,cloud_security,governance,training,consulting'],
            'summary' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'service_scope' => ['nullable', 'array'],
            'deliverables' => ['nullable', 'array'],
            'target_audience' => ['nullable', 'array'],
            'ai_keywords' => ['nullable', 'array'],
            'ai_domain' => ['nullable', 'string'],
            'is_ai_visible' => ['boolean'],
            'cta_label' => ['nullable', 'string', 'max:255'],
            'cta_url' => ['nullable', 'string', 'max:255'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:300'],
            'seo_keywords' => ['nullable', 'array'],
            'is_active' => ['boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $jsonFields = [
            'service_scope',
            'deliverables',
            'target_audience',
            'ai_keywords',
            'seo_keywords',
        ];

        foreach ($jsonFields as $field) {
            if ($this->has($field)) {
                $val = $this->input($field);
                if (is_string($val)) {
                    $decoded = json_decode($val, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $this->merge([$field => $decoded]);
                    }
                }
            }
        }

        $sanitizeMap = [
            'name' => 'cleanString',
            'short_name' => 'cleanString',
            'summary' => 'sanitizeHtml',
            'description' => 'sanitizeHtml',
            'cta_label' => 'cleanString',
            'seo_title' => 'cleanString',
            'seo_description' => 'cleanString',
        ];

        $toMerge = [];

        foreach ($sanitizeMap as $field => $method) {
            if ($this->has($field)) {
                $toMerge[$field] = SecurityHelper::$method($this->input($field));
            }
        }

        if ($toMerge) {
            $this->merge($toMerge);
        }

        // Ensure defaults for optional numeric/boolean fields
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'is_ai_visible' => $this->boolean('is_ai_visible'),
            'sort_order' => $this->input('sort_order') ?? 0,
        ]);
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama layanan wajib diisi.',
            'name.unique' => 'Nama layanan ini sudah digunakan.',
            'category.required' => 'Kategori layanan wajib dipilih.',
            'category.in' => 'Kategori yang dipilih tidak valid.',
            'summary.required' => 'Ringkasan eksekutif wajib diisi.',
            'sort_order.integer' => 'Urutan tampilan harus berupa angka.',
            'thumbnail.image' => 'File harus berupa gambar.',
            'thumbnail.max' => 'Ukuran gambar maksimal adalah 2MB.',
        ];
    }
}
