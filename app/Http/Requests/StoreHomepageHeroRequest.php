<?php

namespace App\Http\Requests;

use App\Helpers\SecurityHelper;
use Illuminate\Foundation\Http\FormRequest;

class StoreHomepageHeroRequest extends FormRequest
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
            'pre_title'             => 'required|string|max:100',
            'title'                 => 'required|string|max:255',
            'subtitle'              => 'required|string|max:500',
            'primary_button_text'   => 'required|string|max:100',
            'primary_button_url'    => 'required|string|max:255',
            'secondary_button_text' => 'required|string|max:100',
            'secondary_button_url'  => 'required|string|max:255',
            'is_active'             => 'required|boolean',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $toMerge = [];

        $fieldsToClean = [
            'pre_title', 'title', 'subtitle', 
            'primary_button_text', 'primary_button_url', 
            'secondary_button_text', 'secondary_button_url'
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
