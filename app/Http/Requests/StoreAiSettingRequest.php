<?php

namespace App\Http\Requests;

use App\Helpers\SecurityHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAiSettingRequest extends FormRequest
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
        return [
            'provider' => ['required', 'string', Rule::in(['openai', 'azure', 'custom'])],
            'base_url' => ['nullable', 'string', 'max:255', 'url'],
            'api_key' => ['nullable', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:100'],
            'temperature' => ['required', 'numeric', 'min:0', 'max:2'],
            'max_tokens' => ['required', 'integer', 'min:1', 'max:8192'],
            'timeout' => ['required', 'integer', 'min:1', 'max:120'],
            'is_active' => ['boolean'],
            'cybersecurity_only' => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $toMerge = [];

        if ($this->has('base_url')) {
            $toMerge['base_url'] = SecurityHelper::cleanString($this->input('base_url'));
        }

        if ($this->has('model')) {
            $toMerge['model'] = SecurityHelper::cleanString($this->input('model'));
        }

        if (!empty($toMerge)) {
            $this->merge($toMerge);
        }
    }
}
