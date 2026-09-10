<?php

namespace Templates\RealEstate\Models;

use App\Models\Concerns\HasSlug;
use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Templates\RealEstate\Database\Factories\ListingFactory;

#[UseFactory(ListingFactory::class)]
#[Fillable(['title', 'slug', 'listing_type', 'property_type', 'price', 'currency', 'city', 'district', 'address', 'rooms', 'bathrooms', 'area_gross', 'area_net', 'floor', 'building_age', 'heating', 'furnished', 'description', 'features', 'image', 'gallery', 'video_url', 'map_embed', 'agent_id', 'is_active', 'is_featured', 'sort_order', 'meta_title', 'meta_description'])]
class Listing extends Model
{
    use HasFactory, HasSlug;

    public const TYPES = ['sale' => 'Satılık', 'rent' => 'Kiralık'];

    public const PROPERTY_TYPES = ['apartment' => 'Daire', 'villa' => 'Villa', 'residence' => 'Rezidans', 'office' => 'Ofis', 'shop' => 'Dükkan', 'land' => 'Arsa', 'warehouse' => 'Depo', 'building' => 'Bina'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'furnished' => 'boolean',
            'features' => 'array',
            'gallery' => 'array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(TeamMember::class, 'agent_id');
    }

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', true);
    }

    #[Scope]
    protected function ordered(Builder $query): void
    {
        $query->orderByDesc('is_featured')->orderBy('sort_order')->orderByDesc('id');
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->listing_type] ?? $this->listing_type;
    }

    public function propertyTypeLabel(): string
    {
        return self::PROPERTY_TYPES[$this->property_type] ?? $this->property_type;
    }

    public function currencySymbol(): string
    {
        return match ($this->currency) {
            'USD' => '$',
            'EUR' => '€',
            default => '₺',
        };
    }

    public function formattedPrice(): ?string
    {
        if ($this->price === null) {
            return null;
        }

        return number_format((float) $this->price, 0, ',', '.').' '.$this->currencySymbol().($this->listing_type === 'rent' ? ' / ay' : '');
    }

    /**
     * Price with the currency symbol first, as shown on listing cards ("₺ 42.000.000", "₺ 45.000 / Ay").
     */
    public function displayPrice(): ?string
    {
        if ($this->price === null) {
            return null;
        }

        return $this->currencySymbol().' '.number_format((float) $this->price, 0, ',', '.').($this->listing_type === 'rent' ? ' / Ay' : '');
    }

    public function url(): string
    {
        return route('listings.show', $this->slug);
    }

    public function location(): string
    {
        return implode(', ', array_filter([$this->district, $this->city]));
    }

    /**
     * @return array<int, string>
     */
    public function allImages(): array
    {
        return array_values(array_filter(array_unique([$this->image, ...($this->gallery ?? [])])));
    }
}
