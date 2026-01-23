<?php

namespace App\Http\Requests;

use App\Helpers\SecurityHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        if (!$user) {
            return false;
        }

        return $user->role === 'admin'
            || (method_exists($user, 'can') && $user->can('manage-product'));
    }

    public function rules(): array
    {
        return [
            'product_name' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'summary' => 'nullable|string',
            'content' => 'nullable|string',

            'product_type' => 'required|in:digital,hardware,service,bundle',

            'ai_domain' => [
                'nullable',
                'string',
                Rule::in([
                    'general',
                    'network_security',
                    'application_security',
                    'cloud_security',
                    'soc',
                    'pentest',
                    'malware',
                    'incident_response',
                    'governance',
                ]),
            ],

            'ai_level' => [
                'nullable',
                'string',
                Rule::in(['beginner', 'intermediate', 'advanced', 'all']),
            ],

            'ai_keywords' => 'nullable|array',
            'ai_intents' => 'nullable|array',
            'ai_use_cases' => 'nullable|array',

            'ai_priority' => 'boolean',

            'is_ai_visible' => 'boolean',
            'is_ai_recommended' => 'boolean',

            'cta_label' => 'nullable|string|max:255',
            'cta_url' => 'nullable|string|max:255',
            'cta_type' => [
                'nullable',
                'string',
                Rule::in(['internal', 'external', 'whatsapp', 'form']),
            ],

            'thumbnail' => 'nullable|image|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|max:4096',

            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:300',
            'seo_keywords' => 'nullable|array',
            'canonical_url' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }

    protected function prepareForValidation(): void
    {
        $payload = $this->all();

        foreach (['ai_keywords', 'ai_intents', 'ai_use_cases', 'seo_keywords'] as $field) {
            if (isset($payload[$field]) && is_string($payload[$field])) {
                $payload[$field] = json_decode($payload[$field], true) ?: [];
            }
        }

        // 🔥 INI INTINYA
        if (empty($payload['ai_priority'])) {
            $payload['ai_priority'] = true;
        }

        $toMerge = [];

        $fieldsToClean = [
            'product_name',
            'subtitle',
            'ai_domain',
            'ai_level',
            'cta_label',
            'cta_url',
            'cta_type',
            'seo_title',
            'seo_description',
            'canonical_url',
        ];

        foreach ($fieldsToClean as $field) {
            if (isset($payload[$field])) {
                $toMerge[$field] = SecurityHelper::cleanString($payload[$field]);
            }
        }

        if (isset($payload['summary'])) {
            $toMerge['summary'] = SecurityHelper::sanitizeHtml($payload['summary']);
        }

        if (isset($payload['content'])) {
            $toMerge['content'] = SecurityHelper::sanitizeHtml($payload['content']);
        }

        foreach (['is_ai_visible', 'is_ai_recommended', 'ai_priority', 'is_active'] as $boolField) {
            if (isset($payload[$boolField])) {
                $toMerge[$boolField] = filter_var(
                    $payload[$boolField],
                    FILTER_VALIDATE_BOOLEAN
                );
            }
        }

        if ($this->has('sort_order')) {
            $toMerge['sort_order'] = (int) $this->input('sort_order');
        } else {
            $payload['sort_order'] = 0;
        }

        $this->merge($payload);
        $this->merge($toMerge);
    }

    public function messages(): array
    {
        return [
            'product_name.required' => 'Nama produk wajib diisi.',
            'product_type.required' => 'Tipe produk wajib dipilih.',
            'product_type.in' => 'Tipe produk yang dipilih tidak valid.',
            'thumbnail.image' => 'File harus berupa gambar.',
            'thumbnail.max' => 'Ukuran thumbnail maksimal adalah 2MB.',
            'images.*.image' => 'Salah satu file galeri bukan gambar.',
            'images.*.max' => 'Ukuran salah satu gambar galeri melebihi 4MB.',
        ];
    }
}
