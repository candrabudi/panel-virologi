<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreAboutUsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }

        // Check for specific permission or admin role
        return (method_exists($user, 'can') && $user->can('manage-website')) 
            || $user->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'breadcrumb_pre'  => ['nullable', 'string', 'max:100'],
            'breadcrumb_bg'   => ['nullable', 'string', 'max:100'],
            'page_title'      => ['nullable', 'string', 'max:150'],
            'headline'        => ['nullable', 'string', 'max:255'],
            'left_content'    => ['nullable', 'string', 'max:10000'],
            'right_content'   => ['nullable', 'string', 'max:10000'],
            'seo_title'       => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:300'],
            'seo_keywords'    => ['nullable', 'string', 'max:500'],
            'og_title'        => ['nullable', 'string', 'max:255'],
            'og_description'  => ['nullable', 'string', 'max:300'],
            'canonical_url'   => ['nullable', 'string', 'max:255'],
            'is_active'       => ['required', Rule::in(['0', '1', 0, 1])],
        ];
    }

    /**
     * Prepare the data for validation.
     * Sanitizes input before validation/processing.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'left_content'  => \App\Helpers\SecurityHelper::sanitizeHtml($this->left_content),
            'right_content' => \App\Helpers\SecurityHelper::sanitizeHtml($this->right_content),
            'seo_title'     => \App\Helpers\SecurityHelper::cleanString($this->seo_title),
            'seo_description' => \App\Helpers\SecurityHelper::cleanString($this->seo_description),
        ]);
    }
}
