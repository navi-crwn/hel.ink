<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginHistory extends Model
{
    protected $fillable = [
        'user_id',
        'ip_address',
        'country',
        'country_name',
        'city',
        'region',
        'provider',
        'isp',
        'device',
        'browser',
        'is_proxy',
        'proxy_type',
        'proxy_confidence',
        'logged_in_at',
    ];

    protected $casts = [
        'logged_in_at' => 'datetime',
        'is_proxy' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
