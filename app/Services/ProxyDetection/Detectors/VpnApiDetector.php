<?php

namespace App\Services\ProxyDetection\Detectors;

class VpnApiDetector extends BaseProxyDetector
{
    protected string $baseUrl = 'https://vpnapi.io/api/';

    public function getName(): string
    {
        return 'VPNApi';
    }

    public function getPriority(): int
    {
        return 4;
    }

    public function getDailyLimit(): int
    {
        return 1000;
    }

    public function detect(string $ip): ?array
    {
        $data = $this->makeRequest("{$this->baseUrl}{$ip}");
        if (! $data) {
            return null;
        }
        $security = $data['security'] ?? [];

        return [
            'is_proxy' => $security['vpn'] ?? false || $security['proxy'] ?? false || $security['tor'] ?? false,
            'type' => $this->determineType($security),
            'confidence' => 85,
            'details' => [
                'vpn' => $security['vpn'] ?? false,
                'proxy' => $security['proxy'] ?? false,
                'tor' => $security['tor'] ?? false,
                'relay' => $security['relay'] ?? false,
            ],
        ];
    }

    protected function determineType(array $security): ?string
    {
        if ($security['tor'] ?? false) {
            return 'tor';
        }
        if ($security['vpn'] ?? false) {
            return 'vpn';
        }
        if ($security['proxy'] ?? false) {
            return 'proxy';
        }
        if ($security['relay'] ?? false) {
            return 'relay';
        }

        return null;
    }
}
