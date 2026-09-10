<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Generic person record: doctor, lawyer, agent, engineer, developer...
 * Templates use "attributes" for sector-specific key/value pairs.
 */
#[Fillable(['name', 'slug', 'title', 'excerpt', 'bio', 'image', 'email', 'phone', 'socials', 'attributes', 'sort_order', 'is_active', 'is_featured'])]
class TeamMember extends Model
{
    use HasFactory, HasSlug;

    protected string $slugSource = 'name';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'socials' => 'array',
            'attributes' => 'array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', true);
    }

    #[Scope]
    protected function ordered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('name');
    }

    public function url(): string
    {
        return route('team.show', $this->slug);
    }

    public function attribute(string $key, mixed $default = null): mixed
    {
        return collect($this->attributes_list())->firstWhere('label', $key)['value'] ?? $default;
    }

    /**
     * @return array<int, array{label: string, value: string}>
     */
    public function attributes_list(): array
    {
        return $this->getAttribute('attributes') ?? [];
    }

    public function social(string $network): ?string
    {
        return $this->socials[$network] ?? null;
    }
}
