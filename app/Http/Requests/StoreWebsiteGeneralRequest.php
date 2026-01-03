<?php

namespace App\Http\Requests;

use App\Helpers\SecurityHelper;
use Illuminate\Foundation\Http\FormRequest;

class StoreWebsiteGeneralRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        if (!$user) {
            return false;
        }

        return $user->role === 'admin'
            || (method_exists($user, 'can') && $user->can('manage-website'));
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ];
    }

    protected function prepareForValidation(): void
    {
        $toMerge = [];

        if ($this->has('name')) {
            $toMerge['name'] = SecurityHelper::cleanString($this->name);
        }

        if ($this->has('tagline')) {
            $toMerge['tagline'] = SecurityHelper::cleanString($this->tagline);
        }

        if ($this->has('description')) {
            $toMerge['description'] = SecurityHelper::cleanString($this->description);
        }

        if (!empty($toMerge)) {
            $this->merge($toMerge);
        }
    }
}
