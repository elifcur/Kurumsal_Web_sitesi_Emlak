<?php

namespace App\Http\Requests\Admin;

use App\Services\HtmlSanitizer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeamMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage-extras') ?? false;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:180', 'regex:/^[a-z0-9\-]+$/', Rule::unique('team_members', 'slug')->ignore($this->route('member'))],
            'title' => ['nullable', 'string', 'max:150'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'bio' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:190'],
            'phone' => ['nullable', 'string', 'max:40'],
            'socials' => ['nullable', 'array'],
            'socials.*' => ['nullable', 'url', 'max:255'],
            'attributes' => ['nullable', 'array', 'max:20'],
            'attributes.*.label' => ['nullable', 'string', 'max:100'],
            'attributes.*.value' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        $data = $this->validated();
        $data['bio'] = app(HtmlSanitizer::class)->clean($data['bio'] ?? null);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['slug'] = ($data['slug'] ?? null) ?: null;
        $data['socials'] = array_filter($data['socials'] ?? []);
        $data['attributes'] = collect($data['attributes'] ?? [])
            ->filter(fn (array $row) => trim((string) ($row['label'] ?? '')) !== '')
            ->map(fn (array $row) => ['label' => trim($row['label']), 'value' => trim((string) ($row['value'] ?? ''))])
            ->values()
            ->all();

        return $data;
    }
}
