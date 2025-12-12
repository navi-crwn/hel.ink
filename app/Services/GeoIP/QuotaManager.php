<?php

namespace App\Services\GeoIP;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class QuotaManager
{
    protected string $cachePrefix = 'geoip:quota:';

    public function getUsage(string $provider, string $period = 'daily'): int
    {
        $key = $this->getCacheKey($provider, $period);

        return (int) Cache::get($key, 0);
    }

    public function increment(string $provider): void
    {
        $dailyKey = $this->getCacheKey($provider, 'daily');
        $dailyTtl = now()->diffInSeconds(now()->endOfDay());
        Cache::add($dailyKey, 0, $dailyTtl);
        Cache::increment($dailyKey);
        $monthlyKey = $this->getCacheKey($provider, 'monthly');
        $monthlyTtl = now()->diffInSeconds(now()->endOfMonth());
        Cache::add($monthlyKey, 0, $monthlyTtl);
        Cache::increment($monthlyKey);
        Log::debug('GeoIP quota incremented', [
            'provider' => $provider,
            'daily' => $this->getUsage($provider, 'daily'),
            'monthly' => $this->getUsage($provider, 'monthly'),
        ]);
    }

    public function hasAvailableQuota(string $provider, int $dailyLimit, int $monthlyLimit): bool
    {
        $dailyUsage = $this->getUsage($provider, 'daily');
        $monthlyUsage = $this->getUsage($provider, 'monthly');
        if ($dailyUsage >= $dailyLimit) {
            Log::warning('Provider daily quota exceeded', [
                'provider' => $provider,
                'usage' => $dailyUsage,
                'limit' => $dailyLimit,
            ]);

            return false;
        }
        if ($monthlyUsage >= $monthlyLimit) {
            Log::warning('Provider monthly quota exceeded', [
                'provider' => $provider,
                'usage' => $monthlyUsage,
                'limit' => $monthlyLimit,
            ]);

            return false;
        }

        return true;
    }

    public function getStats(): array
    {
        $providers = ['FreeIpApi', 'IpApi', 'IpApiCo', 'IpInfo', 'AbstractApi'];
        $stats = [];
        foreach ($providers as $provider) {
            $stats[$provider] = [
                'daily' => $this->getUsage($provider, 'daily'),
                'monthly' => $this->getUsage($provider, 'monthly'),
            ];
        }

        return $stats;
    }

    public function reset(string $provider): void
    {
        Cache::forget($this->getCacheKey($provider, 'daily'));
        Cache::forget($this->getCacheKey($provider, 'monthly'));
    }

    protected function getCacheKey(string $provider, string $period): string
    {
        return "{$this->cachePrefix}{$provider}:{$period}";
    }
}
