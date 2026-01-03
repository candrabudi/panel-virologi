<?php

namespace App\Http\Requests;

use App\Helpers\SecurityHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAiContextRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->role === 'admin' || (method_exists(auth()->user(), 'can') && auth()->user()->can('manage-ai')));
    }

    /**
     * Get the validation rules that apply to the request.
     */
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
            'name'                => 'required|string|min:3|max:100',
            'use_internal_source' => 'required|boolean',
            'is_active'           => 'sometimes|boolean',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'code'                => $this->code ? strtolower(trim($this->code)) : null,
            'name'                => SecurityHelper::cleanString($this->name),
            'use_internal_source' => filter_var($this->use_internal_source, FILTER_VALIDATE_BOOLEAN),
            'is_active'           => $this->has('is_active') ? filter_var($this->is_active, FILTER_VALIDATE_BOOLEAN) : null,
        ]);
        
        // Remove nulls so 'sometimes' works correctly
        $this->replace(array_filter($this->all(), fn($v) => !is_null($v)));
    }
}
