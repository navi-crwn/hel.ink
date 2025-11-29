<?php

namespace App\Services\GeoIP\Providers;

class FreeIpApiProvider extends BaseGeoIpProvider
{
    protected string $baseUrl = 'https://freeipapi.com/api/json/';

    public function getName(): string
    {
        return 'FreeIpApi';
    }

    public function getPriority(): int
    {
        return 1; // Highest priority - best free tier
    }

    public function getDailyLimit(): int
    {
        return 60 * 60 * 24; // 60 req/min = ~86,400/day
    }

    public function getMonthlyLimit(): int
    {
        return 2_592_000; // ~2.5M/month
    }

    public function lookup(string $ip): ?array
    {
        return $this->makeRequest("{$this->baseUrl}{$ip}");
    }

    protected function normalize(array $data): array
    {
        return [
            'country' => $data['countryCode'] ?? null,
            'country_name' => $data['countryName'] ?? null,
            'city' => $data['cityName'] ?? null,
            'region' => $data['regionName'] ?? null,
            'provider' => null, // Free tier doesn't include ISP
        ];
    }
}
