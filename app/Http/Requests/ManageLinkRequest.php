<?php

namespace App\Http\Requests;

use App\Models\DomainBlacklist;
use App\Models\Link;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ManageLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $linkId = $this->route('link')?->id;

        return [
            'target_url' => ['required', 'string', 'max:2000'],
            'slug' => [
                'nullable',
                'regex:/^[a-z0-9-]+$/i',
                'min:3',
                'max:48',
                Rule::unique('links', 'slug')->ignore($linkId),
                Rule::notIn(config('shortener.reserved_slugs', [])),
            ],
            'status' => ['required', Rule::in([Link::STATUS_ACTIVE, Link::STATUS_INACTIVE])],
            'password' => ['nullable', 'string', 'min:6'],
            'expires_at' => ['nullable', 'date', 'after:now'],
            'redirect_type' => ['nullable', Rule::in(['301', '302', '307'])],
            'custom_title' => ['nullable', 'string', 'max:255'],
            'custom_description' => ['nullable', 'string', 'max:1000'],
            'custom_image_url' => ['nullable', 'url', 'max:500'],
            'folder_id' => [
                'nullable',
                Rule::exists('folders', 'id')->where(fn ($query) => $query->where('user_id', $this->user()?->id)),
            ],
            'tags' => ['nullable', 'array'],
            'tags.*' => [
                Rule::exists('tags', 'id')->where(fn ($query) => $query->where('user_id', $this->user()?->id)),
            ],
            'comment' => ['nullable', 'string', 'max:500'],
            'remove_password' => ['nullable', 'boolean'],
            'qr_fg_color' => ['nullable', 'string', 'max:7'],
            'qr_bg_color' => ['nullable', 'string', 'max:7'],
            'qr_logo_url' => ['nullable', 'url', 'max:500'],
        ];
    }
    
    protected function prepareForValidation()
    {
        $url = $this->input('target_url');
        if ($url && !preg_match('/^https?:\/\//i', $url)) {
            $this->merge([
                'target_url' => 'https://' . $url,
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
            
            if ($url && !filter_var($url, FILTER_VALIDATE_URL)) {
                $validator->errors()->add('target_url', 'Invalid URL format. Please check the URL format.');
            }
            
            if ($url) {
                $blocked = DomainBlacklist::isBlocked($url);
                if ($blocked) {
                    $category = $blocked['category'] ? ' (' . ucfirst($blocked['category']) . ')' : '';
                    $validator->errors()->add('target_url', 'This domain is not allowed due to our security policy' . $category . '.');
                }
            }
        });
    }
}
