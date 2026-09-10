<?php

namespace App\Http\Requests\Admin;

use App\Services\HtmlSanitizer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
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
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:180', 'regex:/^[a-z0-9\-]+$/', Rule::unique('products', 'slug')->ignore($this->route('product'))],
            'category_id' => ['nullable', 'integer', Rule::exists('categories', 'id')->where('type', 'product')],
            'sku' => ['nullable', 'string', 'max:100'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0', 'max:999999999'],
            'price_note' => ['nullable', 'string', 'max:100'],
            'image' => ['nullable', 'string', 'max:255'],
            'gallery' => ['nullable', 'array', 'max:20'],
            'gallery.*' => ['string', 'max:255'],
            'specs' => ['nullable', 'array', 'max:50'],
            'specs.*.label' => ['nullable', 'string', 'max:100'],
            'specs.*.value' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
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
        $data['content'] = app(HtmlSanitizer::class)->clean($data['content'] ?? null);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['slug'] = ($data['slug'] ?? null) ?: null;
        $data['category_id'] = ($data['category_id'] ?? null) ?: null;
        $data['price'] = isset($data['price']) && $data['price'] !== '' ? $data['price'] : null;
        $data['gallery'] = array_values(array_filter($data['gallery'] ?? []));
        $data['specs'] = array_values(array_filter($data['specs'] ?? [], fn ($row) => ! empty($row['label'])));

        return $data;
    }
}
