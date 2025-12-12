<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BioClick extends Model
{
    use HasFactory;

    protected $fillable = [
        'bio_link_id',
        'clicked_at',
        'country',
        'device',
        'browser',
        'referrer',
        'ip_address',
    ];

    protected $casts = [
        'clicked_at' => 'datetime',
    ];

    public function bioLink(): BelongsTo
    {
        return $this->belongsTo(BioLink::class);
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('clicked_at', '>=', now()->subDays($days));
    }

    public function scopeByCountry($query, string $country)
    {
        return $query->where('country', $country);
    }
}
