<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWebsiteBrandingRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && ($user->role === 'admin' || (method_exists($user, 'can') && $user->can('manage-website')));
    }

    public function rules(): array
    {
        return [
            'logo_rectangle' => 'nullable|image|mimes:png,jpg,jpeg|max:4096',
            'logo_square' => 'nullable|image|mimes:png,jpg,jpeg|max:4096',
            'favicon' => 'nullable|image|mimes:png,ico|max:1024',
        ];
    }
}
