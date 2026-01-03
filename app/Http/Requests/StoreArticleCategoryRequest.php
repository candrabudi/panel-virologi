<?php

namespace App\Http\Requests;

use App\Helpers\SecurityHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreArticleCategoryRequest extends FormRequest
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

        // Consistent with ArticleCategoryController::authorizeManage
        return $user->role === 'admin' || 
               (method_exists($user, 'can') && $user->can('manage-article'));
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $categoryId = $this->route('id'); // for update

        return [
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('article_categories', 'name')->ignore($categoryId),
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
                'name' => SecurityHelper::cleanString($this->name),
            ]);
        }
    }
}
