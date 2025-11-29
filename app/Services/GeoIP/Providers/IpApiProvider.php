<?php

namespace App\Services\GeoIP\Providers;

class IpApiProvider extends BaseGeoIpProvider
{
    protected string $baseUrl = 'http://ip-api.com/json/';
    protected string $fields = '66846719';

    public function getName(): string
    {
        return 'IpApi';
    }

    public function getPriority(): int
    {
        return 2;
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
        $data = $this->makeRequest("{$this->baseUrl}{$ip}", [
            'fields' => $this->fields
        ]);
        if ($data && isset($data['status']) && $data['status'] === 'fail') {
            return null;
        }

        return $data;
    }

    protected function normalize(array $data): array
    {
        return [
            'country' => $data['countryCode'] ?? null,
            'country_name' => $data['country'] ?? null,
            'city' => $data['city'] ?? null,
            'region' => $data['regionName'] ?? null,
            'provider' => $data['isp'] ?? null,
        ];
    }
}
