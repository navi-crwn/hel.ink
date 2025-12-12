<?php

namespace App\Services\ProxyDetection\Detectors;

class ProxyCheckDetector extends BaseProxyDetector
{
    protected string $baseUrl = 'https://proxycheck.io/v2/';

    protected ?string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.proxycheck.api_key');
    }

    public function getName(): string
    {
        return 'ProxyCheck';
    }

    public function getPriority(): int
    {
        return 1;
    }

    public function getDailyLimit(): int
    {
        return $this->apiKey ? 10000 : 1000; // With key: 10k, without: 1k
    }

    public function detect(string $ip): ?array
    {
        $params = ['vpn' => 1, 'asn' => 1];
        if ($this->apiKey) {
            $params['key'] = $this->apiKey;
        }
        $data = $this->makeRequest("{$this->baseUrl}{$ip}", $params);
        if (! $data || ! isset($data[$ip])) {
            return null;
        }
        $result = $data[$ip];

        return [
            'is_proxy' => ($result['proxy'] ?? 'no') === 'yes',
            'type' => $result['type'] ?? null, // VPN, SOCKS, HTTP, etc
            'confidence' => 95, // ProxyCheck is very reliable
            'details' => [
                'provider' => $result['provider'] ?? null,
                'asn' => $result['asn'] ?? null,
            ],
        ];
    }
}
