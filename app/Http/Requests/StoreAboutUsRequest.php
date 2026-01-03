<?php

namespace App\Http\Requests;

use App\Helpers\SecurityHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreAboutUsRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = Auth::user();

        return $user
            && ($user->role === 'admin'
                || (method_exists($user, 'can') && $user->can('manage-website')));
    }

    public function rules(): array
    {
        return [
            'breadcrumb_pre' => ['nullable', 'string', 'max:100'],
            'breadcrumb_bg' => ['nullable', 'string', 'max:100'],
            'page_title' => ['nullable', 'string', 'max:150'],
            'headline' => ['nullable', 'string', 'max:255'],
            'left_content' => ['nullable', 'string', 'max:10000'],
            'right_content' => ['nullable', 'string', 'max:10000'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:300'],
            'seo_keywords' => ['nullable', 'string', 'max:500'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string', 'max:300'],
            'canonical_url' => ['nullable', 'string', 'max:255'],
            'is_active' => ['required', Rule::in(['0', '1', 0, 1])],
        ];
    }

    protected function prepareForValidation(): void
    {
        $toMerge = [];

        if ($this->has('left_content')) {
            $toMerge['left_content'] = SecurityHelper::sanitizeHtml($this->input('left_content'));
        }

        if ($this->has('right_content')) {
            $toMerge['right_content'] = SecurityHelper::sanitizeHtml($this->input('right_content'));
        }

        if ($this->has('seo_title')) {
            $toMerge['seo_title'] = SecurityHelper::cleanString($this->input('seo_title'));
        }

        if ($this->has('seo_description')) {
            $toMerge['seo_description'] = SecurityHelper::cleanString($this->input('seo_description'));
        }

        if (!empty($toMerge)) {
            $this->merge($toMerge);
        }
    }
}
