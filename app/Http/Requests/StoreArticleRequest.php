<?php

namespace App\Http\Requests;

use App\Helpers\SecurityHelper;
use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string|max:50000',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:article_categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:article_tags,id',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:300',
            'seo_keywords' => 'nullable|string|max:500',
            'is_published' => 'nullable|in:0,1,true,false',
        ];
    }

    protected function prepareForValidation(): void
    {
        $data = [];

        if ($this->has('content')) {
            $data['content'] = SecurityHelper::sanitizeHtml($this->input('content'));
        }

        if ($this->has('excerpt')) {
            $data['excerpt'] = SecurityHelper::cleanString($this->input('excerpt'));
        }

        if ($this->has('seo_title')) {
            $data['seo_title'] = SecurityHelper::cleanString($this->input('seo_title'));
        }

        if ($this->has('seo_description')) {
            $data['seo_description'] = SecurityHelper::cleanString($this->input('seo_description'));
        }

        if ($this->has('seo_keywords')) {
            $data['seo_keywords'] = SecurityHelper::cleanString($this->input('seo_keywords'));
        }

        if (!empty($data)) {
            $this->merge($data);
        }
    }
}
