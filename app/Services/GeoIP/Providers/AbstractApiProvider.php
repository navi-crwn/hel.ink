<?php

namespace App\Services\GeoIP\Providers;

class AbstractApiProvider extends BaseGeoIpProvider
{
    protected string $baseUrl = 'https://ipgeolocation.abstractapi.com/v1/';
    protected ?string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.abstractapi.api_key');
    }

    public function getName(): string
    {
        return 'AbstractApi';
    }

    public function getPriority(): int
    {
        return 5;
    }

    public function getDailyLimit(): int
    {
        return 666; // ~20k/month free
    }

    public function getMonthlyLimit(): int
    {
        return 20000;
    }

    public function isAvailable(): bool
    {
        return !empty($this->apiKey);
    }

    public function lookup(string $ip): ?array
    {
        if (!$this->apiKey) {
            return null;
        }

        return $this->makeRequest($this->baseUrl, [
            'api_key' => $this->apiKey,
            'ip_address' => $ip
        ]);
    }

    protected function normalize(array $data): array
    {
        return [
            'country' => $data['country_code'] ?? null,
            'country_name' => $data['country'] ?? null,
            'city' => $data['city'] ?? null,
            'region' => $data['region'] ?? null,
            'provider' => $data['connection']['isp_name'] ?? null,
        ];
    }
}
