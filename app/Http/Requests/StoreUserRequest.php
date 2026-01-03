<?php

namespace App\Http\Requests;

use App\Helpers\SecurityHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        if (!$user) {
            return false;
        }

        return $user->role === 'admin'
            || (method_exists($user, 'can') && $user->can('manage-user'));
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');
        $user = $this->route('user');
        $userId = $user?->id;

        return [
            'username' => [
                'required',
                'string',
                'max:100',
                Rule::unique('users', 'username')->ignore($userId),
            ],

            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('users', 'email')->ignore($userId),
            ],

            'password' => ($isUpdate ? 'nullable' : 'required').'|string|min:8',

            'role' => [
                'required',
                Rule::in(['admin', 'editor', 'user']),
            ],

            'status' => [
                'required',
                Rule::in(['active', 'inactive', 'blocked']),
            ],

            'full_name' => 'required|string|max:150',

            'phone_number' => 'nullable|string|max:20',
        ];
    }

    protected function prepareForValidation(): void
    {
        $payload = $this->all();

        // 🔥 DEFAULT VALUE
        // kalau status gak dikirim → active
        if (empty($payload['status'])) {
            $payload['status'] = 'active';
        }

        $toMerge = [];

        if (isset($payload['username'])) {
            $toMerge['username'] = SecurityHelper::cleanString($payload['username']);
        }

        if (isset($payload['email'])) {
            $toMerge['email'] = filter_var(
                $payload['email'],
                FILTER_SANITIZE_EMAIL
            );
        }

        if (isset($payload['full_name'])) {
            $toMerge['full_name'] = SecurityHelper::cleanString($payload['full_name']);
        }

        if (isset($payload['phone_number'])) {
            $toMerge['phone_number'] = SecurityHelper::cleanString($payload['phone_number']);
        }

        $this->merge($payload);
        $this->merge($toMerge);
    }
}
