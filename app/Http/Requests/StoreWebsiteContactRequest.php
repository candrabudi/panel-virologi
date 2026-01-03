<?php

namespace App\Http\Requests;

use App\Helpers\SecurityHelper;
use Illuminate\Foundation\Http\FormRequest;

class StoreWebsiteContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && ($user->role === 'admin' || (method_exists($user, 'can') && $user->can('manage-website')));
    }

    public function rules(): array
    {
        return [
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'phone' => SecurityHelper::cleanString($this->phone),
        ]);
    }
}
