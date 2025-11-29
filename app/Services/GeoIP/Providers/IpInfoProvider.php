<?php

namespace App\Services\GeoIP\Providers;

class IpInfoProvider extends BaseGeoIpProvider
{
    protected string $baseUrl = 'https://ipinfo.io/';
    protected ?string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.ipinfo.api_key');
    }

    public function getName(): string
    {
        return 'IpInfo';
    }

    public function getPriority(): int
    {
        return 4;
    }

    public function getDailyLimit(): int
    {
        return 1666; // ~50k/month free
    }

    public function getMonthlyLimit(): int
    {
        return 50000;
    }

    public function lookup(string $ip): ?array
    {
        $params = [];
        if ($this->apiKey) {
            $params['token'] = $this->apiKey;
        }

        return $this->makeRequest("{$this->baseUrl}{$ip}/json", $params);
    }

    protected function normalize(array $data): array
    {
        return [
            'country' => $data['country'] ?? null,
            'country_name' => null, // IpInfo doesn't return country name in free tier
            'city' => $data['city'] ?? null,
            'region' => $data['region'] ?? null,
            'provider' => $data['org'] ?? null,
        ];
    }
}
