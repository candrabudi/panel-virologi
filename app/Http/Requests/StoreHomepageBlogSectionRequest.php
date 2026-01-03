<?php

namespace App\Http\Requests;

use App\Helpers\SecurityHelper;
use Illuminate\Foundation\Http\FormRequest;

class StoreHomepageBlogSectionRequest extends FormRequest
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
            'title'     => 'required|string|max:255',
            'subtitle'  => 'nullable|string|max:500',
            'is_active' => 'required|boolean',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $toMerge = [];

        if ($this->has('title')) {
            $toMerge['title'] = SecurityHelper::cleanString($this->title);
        }

        if ($this->has('subtitle')) {
            $toMerge['subtitle'] = SecurityHelper::cleanString($this->subtitle);
        }

        // Standardize is_active if it's sent as string "0"/"1"
        if ($this->has('is_active')) {
            $toMerge['is_active'] = filter_var($this->is_active, FILTER_VALIDATE_BOOLEAN);
        }

        if (!empty($toMerge)) {
            $this->merge($toMerge);
        }
    }
}
