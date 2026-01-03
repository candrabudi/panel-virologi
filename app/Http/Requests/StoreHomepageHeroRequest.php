<?php

namespace App\Http\Requests;

use App\Helpers\SecurityHelper;
use Illuminate\Foundation\Http\FormRequest;

class StoreHomepageHeroRequest extends FormRequest
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
            'pre_title' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:500',
            'primary_button_text' => 'required|string|max:100',
            'primary_button_url' => 'required|string|max:255',
            'secondary_button_text' => 'required|string|max:100',
            'secondary_button_url' => 'required|string|max:255',
            'is_active' => 'required|boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $toMerge = [];

        $fields = [
            'pre_title',
            'title',
            'subtitle',
            'primary_button_text',
            'primary_button_url',
            'secondary_button_text',
            'secondary_button_url',
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
