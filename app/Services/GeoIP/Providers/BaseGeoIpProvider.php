<?php

namespace App\Services\GeoIP\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

abstract class BaseGeoIpProvider implements GeoIpProviderInterface
{
    protected int $timeout = 5;

    protected function makeRequest(string $url, array $params = []): ?array
    {
        try {
            $response = Http::timeout($this->timeout)->get($url, $params);

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning("{$this->getName()} request failed", [
                'url' => $url,
                'status' => $response->status()
            ]);

            return null;
        } catch (\Throwable $e) {
            Log::warning("{$this->getName()} request error", [
                'url' => $url,
                'error' => $e->getMessage()
            ]);

            return null;
        }
    }

    public function details(string $ip): array
    {
        $data = $this->lookup($ip);

        if (!$data) {
            return [
                'country' => null,
                'country_name' => null,
                'city' => null,
                'region' => null,
                'provider' => null,
            ];
        }

        return $this->normalize($data);
    }
    abstract protected function normalize(array $data): array;

    public function isAvailable(): bool
    {
        return true;
    }
}
