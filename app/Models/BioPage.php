<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class BioPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'slug',
        'title',
        'bio',
        'avatar_url',
        'avatar_shape',
        'badge',
        'badge_color',
        'theme',
        'layout',
        'background_type',
        'background_value',
        'background_color',
        'background_gradient',
        'background_image',
        'text_color',
        'title_color',
        'bio_color',
        'link_bg_color',
        'link_text_color',
        'button_bg_color',
        'button_text_color',
        'button_shape',
        'button_shadow',
        'header_bg_color',
        'font_family',
        'block_shape',
        'block_shadow',
        'social_icon_color',
        'social_placement',
        'social_links',
        'socials',
        'socials_position',
        'social_icons_position',
        'seo_title',
        'seo_description',
        'seo_noindex',
        'custom_css',
        'is_published',
        'is_adult_content',
        'password_enabled',
        'password',
        'is_public',
        'show_in_directory',
        'hide_branding',
        'google_analytics_id',
        'facebook_pixel_id',
        'tiktok_pixel_id',
        'view_count',
        'allow_indexing',
        'qr_settings',
        'hover_effect',
        'background_animation',
    ];

    protected $casts = [
        'social_links' => 'array',
        'socials' => 'array',
        'qr_settings' => 'array',
        'is_published' => 'boolean',
        'is_adult_content' => 'boolean',
        'password_enabled' => 'boolean',
        'is_public' => 'boolean',
        'show_in_directory' => 'boolean',
        'hide_branding' => 'boolean',
        'seo_noindex' => 'boolean',
        'allow_indexing' => 'boolean',
        'view_count' => 'integer',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function links(): HasMany
    {
        return $this->hasMany(BioLink::class)->orderBy('order');
    }

    public function clicks(): HasManyThrough
    {
        return $this->hasManyThrough(BioClick::class, BioLink::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeBySlug($query, string $slug)
    {
        return $query->where('slug', $slug);
    }

    public function incrementViews(): void
    {
        $this->increment('view_count');
    }

    public function getTotalClicks(): int
    {
        return $this->links()->sum('click_count');
    }

    public function getTopLinks(int $limit = 5)
    {
        return $this->links()
            ->where('is_active', true)
            ->orderByDesc('click_count')
            ->limit($limit)
            ->get();
    }
}
