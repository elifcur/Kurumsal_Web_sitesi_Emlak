<?php

namespace Templates\RealEstate\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Templates\RealEstate\Models\Listing;

class ListingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage-sector') ?? false;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:180', 'regex:/^[a-z0-9\-]+$/', Rule::unique('listings', 'slug')->ignore($this->route('listing'))],
            'listing_type' => ['required', Rule::in(array_keys(Listing::TYPES))],
            'property_type' => ['required', Rule::in(array_keys(Listing::PROPERTY_TYPES))],
            'price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['required', Rule::in(['TRY', 'USD', 'EUR'])],
            'city' => ['nullable', 'string', 'max:80'],
            'district' => ['nullable', 'string', 'max:80'],
            'address' => ['nullable', 'string', 'max:500'],
            'rooms' => ['nullable', 'string', 'max:20'],
            'bathrooms' => ['nullable', 'integer', 'min:0', 'max:20'],
            'area_gross' => ['nullable', 'integer', 'min:0'],
            'area_net' => ['nullable', 'integer', 'min:0'],
            'floor' => ['nullable', 'string', 'max:30'],
            'building_age' => ['nullable', 'integer', 'min:0', 'max:200'],
            'heating' => ['nullable', 'string', 'max:60'],
            'furnished' => ['boolean'],
            'description' => ['nullable', 'string', 'max:10000'],
            'features' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'string', 'max:255'],
            'gallery' => ['nullable', 'array', 'max:30'],
            'gallery.*' => ['string', 'max:255'],
            'video_url' => ['nullable', 'url', 'max:255', 'regex:#^https://(www\.)?(youtube\.com|youtu\.be|vimeo\.com)/#'],
            'map_embed' => ['nullable', 'url', 'max:1000', 'regex:#^https://www\.google\.com/maps/embed#'],
            'agent_id' => ['nullable', 'integer', Rule::exists('team_members', 'id')],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
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
        $data['slug'] = ($data['slug'] ?? null) ?: null;
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['price'] = isset($data['price']) && $data['price'] !== '' ? $data['price'] : null;
        $data['agent_id'] = ($data['agent_id'] ?? null) ?: null;
        $data['gallery'] = array_values(array_filter($data['gallery'] ?? []));
        $data['features'] = collect(explode(',', (string) ($data['features'] ?? '')))->map(fn ($f) => trim($f))->filter()->values()->all();

        return $data;
    }
}
