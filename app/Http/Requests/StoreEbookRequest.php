<?php

namespace App\Http\Requests;

use App\Helpers\SecurityHelper;
use Illuminate\Foundation\Http\FormRequest;

class StoreEbookRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        if (!$user) {
            return false;
        }

        return $user->role === 'admin'
            || $user->role === 'editor'
            || (method_exists($user, 'can') && $user->can('manage-ebook'));
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return [
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string|max:2000',
            'content' => 'nullable|string',
            'level' => 'required|in:beginner,intermediate,advanced',
            'topic' => 'required|in:general,network_security,application_security,cloud_security,soc,pentest,malware,incident_response,governance',
            'ai_keywords' => 'nullable|array',
            'ai_keywords.*' => 'string|max:100',
            'cover_image' => 'nullable|image|max:2048',
            'file' => ($isUpdate ? 'nullable' : 'required').'|file|mimes:pdf|max:20480',
            'author' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
            'is_active' => 'sometimes|boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $toMerge = [];

        if ($this->has('summary')) {
            $toMerge['summary'] = SecurityHelper::sanitizeHtml($this->input('summary'));
        }

        if ($this->has('content')) {
            $toMerge['content'] = $this->input('content');
        }

        if ($this->has('title')) {
            $toMerge['title'] = SecurityHelper::cleanString($this->input('title'));
        }

        if ($this->has('author')) {
            $toMerge['author'] = SecurityHelper::cleanString($this->input('author'));
        }

        if ($this->has('ai_keywords') && is_array($this->ai_keywords)) {
            $toMerge['ai_keywords'] = collect($this->ai_keywords)
                ->map(fn ($k) => SecurityHelper::cleanString($k))
                ->filter()
                ->unique()
                ->values()
                ->toArray();
        }

        if ($toMerge) {
            $this->merge($toMerge);
        }
    }
}
