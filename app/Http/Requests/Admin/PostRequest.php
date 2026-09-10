<?php

namespace App\Http\Requests\Admin;

use App\Enums\PostStatus;
use App\Services\HtmlSanitizer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage-posts') ?? false;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:180', 'regex:/^[a-z0-9\-]+$/', Rule::unique('posts', 'slug')->ignore($this->route('post'))],
            'category_id' => ['nullable', 'integer', Rule::exists('categories', 'id')->where('type', 'post')],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::enum(PostStatus::class)],
            'published_at' => ['nullable', 'date'],
            'tags' => ['nullable', 'string', 'max:500'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:320'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        $data = $this->validated();
        unset($data['tags']);
        $data['content'] = app(HtmlSanitizer::class)->clean($data['content'] ?? null);
        $data['slug'] = ($data['slug'] ?? null) ?: null;
        $data['category_id'] = ($data['category_id'] ?? null) ?: null;

        if ($data['status'] === PostStatus::Published->value && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        return $data;
    }

    /**
     * @return array<int, string>
     */
    public function tagNames(): array
    {
        return collect(explode(',', (string) $this->input('tags')))
            ->map(fn (string $tag) => trim($tag))
            ->filter()
            ->unique()
            ->take(20)
            ->values()
            ->all();
    }
}
