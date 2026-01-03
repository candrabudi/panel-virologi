<?php

namespace App\Http\Requests;

use App\Helpers\SecurityHelper;
use Illuminate\Foundation\Http\FormRequest;

class StoreWebsiteContactRequest extends FormRequest
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
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
        ];
    }

    protected function prepareForValidation(): void
    {
        $toMerge = [];

        if ($this->has('phone')) {
            $toMerge['phone'] = SecurityHelper::cleanString($this->phone);
        }

        if ($this->has('email')) {
            $toMerge['email'] = filter_var($this->email, FILTER_SANITIZE_EMAIL);
        }

        if (!empty($toMerge)) {
            $this->merge($toMerge);
        }
    }
}
