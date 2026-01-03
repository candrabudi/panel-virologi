<?php

namespace App\Http\Requests;

use App\Helpers\SecurityHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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

        // Restrict management to admin or manage-user permission
        return $user->role === 'admin' || 
               (method_exists($user, 'can') && $user->can('manage-user'));
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');
        $user = $this->route('user');
        $userId = $user ? $user->id : null;

        return [
            'username'     => [
                'required', 'string', 'max:100',
                Rule::unique('users', 'username')->ignore($userId),
            ],
            'email'        => [
                'required', 'email', 'max:150',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password'     => ($isUpdate ? 'nullable' : 'required') . '|string|min:8',
            'role'         => ['required', Rule::in(['admin', 'editor', 'user'])],
            'status'       => ['required', Rule::in(['active', 'inactive', 'blocked'])],
            'full_name'    => 'required|string|max:150',
            'phone_number' => 'nullable|string|max:20',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $toMerge = [];

        if ($this->has('username')) {
            $toMerge['username'] = SecurityHelper::cleanString($this->username);
        }

        if ($this->has('email')) {
            $toMerge['email'] = filter_var($this->email, FILTER_SANITIZE_EMAIL);
        }

        if ($this->has('full_name')) {
            $toMerge['full_name'] = SecurityHelper::cleanString($this->full_name);
        }

        if ($this->has('phone_number')) {
            $toMerge['phone_number'] = SecurityHelper::cleanString($this->phone_number);
        }

        if (!empty($toMerge)) {
            $this->merge($toMerge);
        }
    }
}
