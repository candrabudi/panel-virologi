<?php

namespace App\Http\Requests;

use App\Helpers\SecurityHelper;
use Illuminate\Foundation\Http\FormRequest;

class StoreAiChatMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'message'    => 'required|string|min:1|max:5000',
            'session_id' => 'nullable|exists:ai_chat_sessions,id',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('message')) {
            $this->merge([
                'message' => SecurityHelper::cleanString($this->message),
            ]);
        }
    }
}
