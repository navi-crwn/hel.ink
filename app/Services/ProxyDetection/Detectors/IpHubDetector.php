<?php

namespace App\Services\ProxyDetection\Detectors;
class IpHubDetector extends BaseProxyDetector
{
    protected string $baseUrl = 'https://v2.api.iphub.info/ip/';
    protected ?string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.iphub.api_key');
    }

    public function getName(): string
    {
        return 'IPHub';
    }

    public function getPriority(): int
    {
        return 2;
    }

    public function getDailyLimit(): int
    {
        return 1000;
    }

    public function isAvailable(): bool
    {
        return !empty($this->apiKey);
    }

    public function detect(string $ip): ?array
    {
        if (!$this->apiKey) {
            return null;
        }

        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders(['X-Key' => $this->apiKey])
                ->get("{$this->baseUrl}{$ip}");

            if (!$response->successful()) {
                return null;
            }

            $data = $response->json();
            $block = $data['block'] ?? 0;

            return [
                'is_proxy' => $block >= 1,
                'type' => $block === 2 ? 'mixed' : ($block === 1 ? 'datacenter' : null),
                'confidence' => 90,
                'details' => [
                    'isp' => $data['isp'] ?? null,
                    'country' => $data['countryCode'] ?? null,
                ]
            ];
        } catch (\Throwable $e) {
            Log::debug('IPHub detection error', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
