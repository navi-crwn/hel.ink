<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ApiToken extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'token',
        'rate_limit',
        'last_used_at',
        'expires_at',
    ];

    protected $hidden = [
        'token', // Never expose the hashed token
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Generate a new API token
     */
    public static function generate(int $userId, string $name, int $rateLimit = 100, ?\DateTime $expiresAt = null): array
    {
        // Generate a random token (plain text, shown only once)
        $plainToken = 'hlk_'.Str::random(40); // hlk = hel.ink prefix
        // Hash it for storage
        $hashedToken = hash('sha256', $plainToken);
        // Create the token record
        $apiToken = static::create([
            'user_id' => $userId,
            'name' => $name,
            'token' => $hashedToken,
            'rate_limit' => $rateLimit,
            'expires_at' => $expiresAt,
        ]);

        // Return both the model and plain token (only time it's visible)
        return [
            'model' => $apiToken,
            'plain_token' => $plainToken,
        ];
    }

    /**
     * Find token by plain text token
     */
    public static function findByPlainToken(string $plainToken): ?self
    {
        $hashedToken = hash('sha256', $plainToken);

        return static::where('token', $hashedToken)->first();
    }

    /**
     * Check if token is valid (not expired)
     */
    public function isValid(): bool
    {
        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Update last used timestamp
     */
    public function markAsUsed(): void
    {
        $this->update(['last_used_at' => now()]);
    }

    /**
     * Relationship: Token belongs to a user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
