<?php

namespace App\Services\GeoIP\Providers;

class IpApiCoProvider extends BaseGeoIpProvider
{
    protected string $baseUrl = 'https://ipapi.co/';

    public function getName(): string
    {
        return 'IpApiCo';
    }

    public function getPriority(): int
    {
        return 3;
    }

    public function getDailyLimit(): int
    {
        return 1000;
    }

    public function getMonthlyLimit(): int
    {
        return 30000;
    }

    public function lookup(string $ip): ?array
    {
        return $this->makeRequest("{$this->baseUrl}{$ip}/json/");
    }

    protected function normalize(array $data): array
    {
        return [
            'country' => $data['country_code'] ?? null,
            'country_name' => $data['country_name'] ?? null,
            'city' => $data['city'] ?? null,
            'region' => $data['region'] ?? null,
            'provider' => $data['org'] ?? null,
        ];
    }
}
