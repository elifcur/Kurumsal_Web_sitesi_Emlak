<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage-products') ?? false;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $type = $this->input('type', 'product');

        return [
            'type' => ['required', Rule::in(['product', 'post'])],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:180', 'regex:/^[a-z0-9\-]+$/', Rule::unique('categories', 'slug')->where('type', $type)->ignore($this->route('category'))],
            'parent_id' => ['nullable', 'integer', Rule::exists('categories', 'id')->where('type', $type), Rule::notIn([$this->route('category')?->id])],
            'description' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        $data = $this->validated();
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['slug'] = ($data['slug'] ?? null) ?: null;
        $data['parent_id'] = ($data['parent_id'] ?? null) ?: null;

        return $data;
    }
}
