<?php

namespace App\Http\Requests;

use App\Helpers\SecurityHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreArticleTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

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

    protected function prepareForValidation(): void
    {
        if ($this->has('name')) {
            $this->merge([
                'name' => SecurityHelper::cleanString($this->input('name')),
            ]);
        }
    }
}
