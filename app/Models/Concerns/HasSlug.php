<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::saving(function (Model $model): void {
            $source = $model->{$model->slugSourceColumn()};
            $slug = Str::slug((string) ($model->slug ?: $source), '-', 'tr');

            if ($slug === '') {
                $slug = Str::random(8);
            }

            $model->slug = $model->makeSlugUnique($slug);
        });
    }

    protected function slugSourceColumn(): string
    {
        return property_exists($this, 'slugSource') ? $this->slugSource : 'title';
    }

    protected function makeSlugUnique(string $slug): string
    {
        $base = Str::limit($slug, 180, '');
        $candidate = $base;
        $counter = 2;

        while ($this->slugExists($candidate)) {
            $candidate = "{$base}-{$counter}";
            $counter++;
        }

        return $candidate;
    }

    protected function slugExists(string $slug): bool
    {
        $query = static::query()->where('slug', $slug);

        if ($this->exists) {
            $query->whereKeyNot($this->getKey());
        }

        if (method_exists($this, 'slugScopeColumn')) {
            $column = $this->slugScopeColumn();
            $query->where($column, $this->{$column});
        }

        return $query->exists();
    }
}
