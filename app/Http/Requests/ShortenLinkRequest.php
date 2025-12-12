<?php

namespace App\Http\Requests;

use App\Models\DomainBlacklist;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShortenLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'target_url' => ['required', 'string', 'max:2000'],
            'slug' => [
                'nullable',
                'regex:/^[a-z0-9-]+$/i',
                'min:3',
                'max:48',
                Rule::notIn(config('shortener.reserved_slugs', [])),
            ],
            'cf-turnstile-response' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation()
    {
        $url = $this->input('target_url');
        if ($url && ! preg_match('/^https?:\/\//i', $url)) {
            $this->merge([
                'target_url' => 'https://'.$url,
            ]);
        }
    }

    public function messages(): array
    {
        return [
            'slug.regex' => 'Slug can only contain letters, numbers, and hyphens.',
            'target_url.url' => 'Invalid URL format. Please check the URL format.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $url = $this->input('target_url');
            if ($url && ! filter_var($url, FILTER_VALIDATE_URL)) {
                $validator->errors()->add('target_url', 'Invalid URL format. Please check the URL format.');
            }
            if ($url) {
                $blocked = DomainBlacklist::isBlocked($url);
                if ($blocked) {
                    $category = $blocked['category'] ? ' ('.ucfirst($blocked['category']).')' : '';
                    $validator->errors()->add('target_url', 'This domain is not allowed due to our security policy'.$category.'.');
                }
            }
        });
    }
}
