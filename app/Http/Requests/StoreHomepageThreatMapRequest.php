<?php

namespace App\Http\Requests;

use App\Helpers\SecurityHelper;
use Illuminate\Foundation\Http\FormRequest;

class StoreHomepageThreatMapRequest extends FormRequest
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

        // Restrict management to admin or manage-cms permission
        return $user->role === 'admin' || 
               (method_exists($user, 'can') && $user->can('manage-cms'));
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'pre_title'   => 'nullable|string|max:255',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'cta_text'    => 'nullable|string|max:255',
            'cta_url'     => 'nullable|string|max:255',
            'is_active'   => 'required|boolean',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $toMerge = [];

        $fieldsToClean = [
            'pre_title', 'title', 'description', 
            'cta_text', 'cta_url'
        ];

        foreach ($fieldsToClean as $field) {
            if ($this->has($field)) {
                $toMerge[$field] = SecurityHelper::cleanString($this->$field);
            }
        }

        // Standardize is_active
        if ($this->has('is_active')) {
            $toMerge['is_active'] = filter_var($this->is_active, FILTER_VALIDATE_BOOLEAN);
        }

        if (!empty($toMerge)) {
            $this->merge($toMerge);
        }
    }
}
