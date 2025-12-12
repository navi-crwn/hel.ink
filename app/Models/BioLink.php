<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BioLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'bio_page_id',
        'type',
        'title',
        'url',
        'content',
        'description',
        'icon',
        'thumbnail_url',
        'brand',
        'btn_bg_color',
        'btn_text_color',
        'btn_border_color',
        'btn_icon_invert',
        'custom_icon',
        'settings',
        'is_active',
        'order',
        'click_count',
        // Advanced block fields
        'embed_url',
        'countdown_date',
        'countdown_label',
        'map_address',
        'map_zoom',
        'vcard_name',
        'vcard_phone',
        'vcard_email',
        'vcard_company',
        'faq_items',
        'html_content',
        'entrance_animation',
        'attention_animation',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'btn_icon_invert' => 'boolean',
        'order' => 'integer',
        'click_count' => 'integer',
        'settings' => 'array',
        'faq_items' => 'array',
        'countdown_date' => 'datetime',
        'map_zoom' => 'integer',
    ];

    public function bioPage(): BelongsTo
    {
        return $this->belongsTo(BioPage::class);
    }

    public function clicks(): HasMany
    {
        return $this->hasMany(BioClick::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function recordClick(array $data): BioClick
    {
        $click = $this->clicks()->create([
            'clicked_at' => now(),
            'country' => $data['country'] ?? null,
            'device' => $data['device'] ?? null,
            'browser' => $data['browser'] ?? null,
            'referrer' => $data['referrer'] ?? null,
            'ip_address' => $data['ip_address'] ?? null,
        ]);
        $this->incrementClicks();

        return $click;
    }

    public function incrementClicks(): void
    {
        $this->increment('click_count');
    }
}
