<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\SecurityHelper;

class StoreArticleRequest extends FormRequest
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
            'title'           => 'required|string|max:255',
            'excerpt'         => 'nullable|string|max:500',
            'content'         => 'required|string|max:50000',
            'thumbnail'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'categories'      => 'required|array|min:1',
            'categories.*'    => 'exists:article_categories,id',
            'tags'            => 'nullable|array',
            'tags.*'          => 'exists:article_tags,id',
            'seo_title'       => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:300',
            'seo_keywords'    => 'nullable|string|max:500',
            'is_published'    => 'nullable|in:0,1,true,false',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'content'         => SecurityHelper::sanitizeHtml($this->input('content')),
            'excerpt'         => SecurityHelper::cleanString($this->input('excerpt')),
            'seo_title'       => SecurityHelper::cleanString($this->input('seo_title')),
            'seo_description' => SecurityHelper::cleanString($this->input('seo_description')),
            'seo_keywords'    => SecurityHelper::cleanString($this->input('seo_keywords')),
        ]);
    }
}
