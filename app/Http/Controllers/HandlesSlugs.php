<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

trait HandlesSlugs
{
    protected function prepareSlug(?string $slug, ?Link $ignore = null): string
    {
        $normalized = $this->ensureSlugAvailable($slug, $ignore);

        return $normalized ?? $this->generateUniqueSlug();
    }

    protected function ensureSlugAvailable(?string $slug, ?Link $ignore = null): ?string
    {
        $slug = $this->normalizeSlug($slug);
        if (! $slug) {
            return null;
        }
        if ($this->isReservedSlug($slug)) {
            throw ValidationException::withMessages([
                'slug' => 'Slug ini disediakan untuk sistem.',
            ]);
        }
        $exists = Link::query()
            ->where('slug', $slug)
            ->when($ignore, fn ($query) => $query->where('id', '!=', $ignore->id))
            ->exists();
        if ($exists) {
            throw ValidationException::withMessages([
                'slug' => 'Slug sudah dipakai.',
            ]);
        }

        return $slug;
    }

    protected function generateUniqueSlug(?int $length = null): string
    {
        $length = $length ?? config('shortener.random_slug_length', 6);
        do {
            $slug = Str::random($length);
        } while ($this->isReservedSlug($slug) || Link::where('slug', $slug)->exists());

        return $slug;
    }

    protected function normalizeSlug(?string $slug): ?string
    {
        return $slug ? Str::of($slug)->lower()->toString() : null;
    }

    protected function isReservedSlug(string $slug): bool
    {
        $reserved = config('shortener.reserved_slugs', []);

        return in_array($this->normalizeSlug($slug), array_map('strtolower', $reserved), true);
    }
}
