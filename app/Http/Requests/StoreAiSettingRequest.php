<?php

namespace App\Http\Requests;

use App\Helpers\SecurityHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAiSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth()->user();
        return $user && ($user->role === 'admin' || (method_exists($user, 'can') && $user->can('manage-ai')));
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'provider'           => ['required', 'string', Rule::in(['openai', 'azure', 'custom'])],
            'base_url'           => ['nullable', 'string', 'max:255', 'url'],
            'api_key'            => ['nullable', 'string', 'max:255'],
            'model'              => ['required', 'string', 'max:100'],
            'temperature'        => ['required', 'numeric', 'min:0', 'max:2'],
            'max_tokens'         => ['required', 'integer', 'min:1', 'max:8192'],
            'timeout'            => ['required', 'integer', 'min:1', 'max:120'],
            'is_active'          => ['boolean'],
            'cybersecurity_only' => ['boolean'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'base_url' => SecurityHelper::cleanString($this->input('base_url')),
            'model'    => SecurityHelper::cleanString($this->input('model')),
        ]);
    }
}
