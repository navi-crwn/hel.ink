<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DomainBlacklist extends Model
{
    protected $table = 'domain_blacklist';
    
    protected $fillable = [
        'domain',
        'match_type',
        'category',
        'notes',
    ];
    
    public static function isBlocked(string $url): ?array
    {
        $host = parse_url($url, PHP_URL_HOST);
        if (!$host) {
            return null;
        }
        
        $host = strtolower($host);
        $host = preg_replace('/^www\./', '', $host);
        
        $exactMatch = static::where('match_type', 'exact')
            ->where('domain', $host)
            ->first();
        
        if ($exactMatch) {
            return [
                'blocked' => true,
                'domain' => $exactMatch->domain,
                'category' => $exactMatch->category,
                'match_type' => 'exact',
            ];
        }
        
        $wildcards = static::where('match_type', 'wildcard')->get();
        
        foreach ($wildcards as $wildcard) {
            $pattern = str_replace(['.', '*'], ['\.', '.*'], $wildcard->domain);
            $pattern = '/^' . $pattern . '$/i';
            
            if (preg_match($pattern, $host)) {
                return [
                    'blocked' => true,
                    'domain' => $wildcard->domain,
                    'category' => $wildcard->category,
                    'match_type' => 'wildcard',
                ];
            }
        }
        
        return null;
    }
}
