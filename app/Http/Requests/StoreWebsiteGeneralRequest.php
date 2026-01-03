<?php

namespace App\Http\Requests;

use App\Helpers\SecurityHelper;
use Illuminate\Foundation\Http\FormRequest;

class StoreWebsiteGeneralRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && ($user->role === 'admin' || (method_exists($user, 'can') && $user->can('manage-website')));
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
        $this->merge([
            'name' => SecurityHelper::cleanString($this->name),
            'tagline' => SecurityHelper::cleanString($this->tagline),
            'description' => SecurityHelper::cleanString($this->description),
        ]);
    }
}
