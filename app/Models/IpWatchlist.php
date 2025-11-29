<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IpWatchlist extends Model
{
    protected $table = 'ip_watchlist';
    
    protected $fillable = [
        'ip_address',
        'user_id',
        'reason',
        'notes',
        'attempt_count',
        'last_attempt_at',
    ];
    
    protected $casts = [
        'last_attempt_at' => 'datetime',
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public static function addOrUpdate(string $ip, ?int $userId = null, string $reason = 'Suspicious activity'): self
    {
        $watchlist = static::where('ip_address', $ip)->first();
        
        if ($watchlist) {
            $watchlist->increment('attempt_count');
            $watchlist->update(['last_attempt_at' => now()]);
        } else {
            $watchlist = static::create([
                'ip_address' => $ip,
                'user_id' => $userId,
                'reason' => $reason,
                'last_attempt_at' => now(),
            ]);
        }
        
        return $watchlist;
    }
}
