<?php

namespace App\Http\Requests;

use App\Helpers\SecurityHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAiContextRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return $user
            && ($user->role === 'admin'
                || (method_exists($user, 'can') && $user->can('manage-ai')));
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'code' => [
                $this->isMethod('post') ? 'required' : 'sometimes',
                'string',
                'min:3',
                'max:50',
                'regex:/^[a-z0-9_\-]+$/i',
                Rule::unique('ai_contexts', 'code')->ignore($id),
            ],
            'name' => 'required|string|min:3|max:100',
            'use_internal_source' => 'required|boolean',
            'is_active' => 'sometimes|boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $toMerge = [];

        if ($this->has('code')) {
            $toMerge['code'] = strtolower(trim($this->input('code')));
        }

        if ($this->has('name')) {
            $toMerge['name'] = SecurityHelper::cleanString($this->input('name'));
        }

        if ($this->has('use_internal_source')) {
            $toMerge['use_internal_source'] = filter_var(
                $this->input('use_internal_source'),
                FILTER_VALIDATE_BOOLEAN
            );
        }

        if ($this->has('is_active')) {
            $toMerge['is_active'] = filter_var(
                $this->input('is_active'),
                FILTER_VALIDATE_BOOLEAN
            );
        }

        if (!empty($toMerge)) {
            $this->merge($toMerge);
        }
    }
}
