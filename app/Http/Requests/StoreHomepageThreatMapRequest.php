<?php

namespace App\Http\Requests;

use App\Helpers\SecurityHelper;
use Illuminate\Foundation\Http\FormRequest;

class StoreHomepageThreatMapRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return $user
            && ($user->role === 'admin'
                || (method_exists($user, 'can') && $user->can('manage-cms')));
    }

    public function rules(): array
    {
        return [
            'pre_title' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'cta_text' => 'nullable|string|max:255',
            'cta_url' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $toMerge = [];

        $fields = [
            'pre_title',
            'title',
            'description',
            'cta_text',
            'cta_url',
        ];

        foreach ($fields as $field) {
            if ($this->has($field)) {
                $toMerge[$field] = SecurityHelper::cleanString($this->input($field));
            }
        }

        if ($this->has('is_active')) {
            $toMerge['is_active'] = filter_var($this->input('is_active'), FILTER_VALIDATE_BOOLEAN);
        }

        if (!empty($toMerge)) {
            $this->merge($toMerge);
        }
    }
}
