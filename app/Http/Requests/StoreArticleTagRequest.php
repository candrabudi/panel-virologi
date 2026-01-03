<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\SecurityHelper;
use Illuminate\Validation\Rule;

class StoreArticleTagRequest extends FormRequest
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
        $id = $this->route('tag') ?: $this->input('id');

        return [
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('article_tags', 'name')->ignore($id),
            ],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('name')) {
            $this->merge([
                'name' => SecurityHelper::cleanString($this->input('name')),
            ]);
        }
    }
}
