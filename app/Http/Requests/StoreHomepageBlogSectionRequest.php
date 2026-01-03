<?php

namespace App\Http\Requests;

use App\Helpers\SecurityHelper;
use Illuminate\Foundation\Http\FormRequest;

class StoreHomepageBlogSectionRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'is_active' => 'required|boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $toMerge = [];

        if ($this->has('title')) {
            $toMerge['title'] = SecurityHelper::cleanString($this->input('title'));
        }

        if ($this->has('subtitle')) {
            $toMerge['subtitle'] = SecurityHelper::cleanString($this->input('subtitle'));
        }

        if ($this->has('is_active')) {
            $toMerge['is_active'] = filter_var($this->input('is_active'), FILTER_VALIDATE_BOOLEAN);
        }

        if (!empty($toMerge)) {
            $this->merge($toMerge);
        }
    }
}
