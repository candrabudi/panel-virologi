<?php

namespace App\Http\Requests;

use App\Helpers\SecurityHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        if (!$user) {
            return false;
        }

        // Restrict management to admin or manage-product permission
        return $user->role === 'admin' || 
               (method_exists($user, 'can') && $user->can('manage-product'));
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'product_name'      => 'required|string|max:255',
            'subtitle'          => 'nullable|string|max:255',
            'summary'           => 'nullable|string',
            'content'           => 'nullable|string',

            'product_type'      => 'required|in:digital,hardware,service,bundle',
            'ai_domain'         => ['nullable', 'string', Rule::in([
                'general', 'network_security', 'application_security', 'cloud_security', 
                'soc', 'pentest', 'malware', 'incident_response', 'governance'
            ])],
            'ai_level'          => ['nullable', 'string', Rule::in(['beginner', 'intermediate', 'advanced', 'all'])],

            'ai_keywords'       => 'nullable|array',
            'ai_intents'        => 'nullable|array',
            'ai_use_cases'      => 'nullable|array',

            'ai_priority'       => 'nullable|integer|min:0',
            'is_ai_visible'     => 'boolean',
            'is_ai_recommended' => 'boolean',

            'cta_label'         => 'nullable|string|max:255',
            'cta_url'           => 'nullable|string|max:255',
            'cta_type'          => ['nullable', 'string', Rule::in(['internal', 'external', 'whatsapp', 'form'])],

            'thumbnail'         => 'nullable|image|max:2048',
            'images'            => 'nullable|array',
            'images.*'          => 'nullable|image|max:4096',

            'seo_title'         => 'nullable|string|max:255',
            'seo_description'   => 'nullable|string|max:300',
            'seo_keywords'      => 'nullable|array',
            'canonical_url'     => 'nullable|string|max:255',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $payload = $this->all();

        // Handle JSON fields that might come as strings from frontend
        foreach (['ai_keywords', 'ai_intents', 'ai_use_cases', 'seo_keywords'] as $field) {
            if (isset($payload[$field]) && is_string($payload[$field])) {
                $payload[$field] = json_decode($payload[$field], true) ?: [];
            }
        }

        $toMerge = [];

        // Manual sanitization for fields that need it
        $fieldsToClean = [
            'product_name', 'subtitle', 'ai_domain', 'ai_level', 
            'cta_label', 'cta_url', 'cta_type', 
            'seo_title', 'seo_description', 'canonical_url'
        ];

        foreach ($fieldsToClean as $field) {
            if (isset($payload[$field])) {
                $toMerge[$field] = SecurityHelper::cleanString($payload[$field]);
            }
        }

        // Rich text sanitization
        if (isset($payload['summary'])) {
            $toMerge['summary'] = SecurityHelper::sanitizeHtml($payload['summary']);
        }
        if (isset($payload['content'])) {
            $toMerge['content'] = SecurityHelper::sanitizeHtml($payload['content']);
        }

        // Booleans
        if (isset($payload['is_ai_visible'])) {
            $toMerge['is_ai_visible'] = filter_var($payload['is_ai_visible'], FILTER_VALIDATE_BOOLEAN);
        }
        if (isset($payload['is_ai_recommended'])) {
            $toMerge['is_ai_recommended'] = filter_var($payload['is_ai_recommended'], FILTER_VALIDATE_BOOLEAN);
        }

        // Merge JSON-decoded fields first
        $this->merge($payload);

        // Then merge sanitized fields to ensure they take precedence
        if (!empty($toMerge)) {
            $this->merge($toMerge);
        }
    }
}
