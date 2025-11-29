<?php

namespace App\Services\ProxyDetection\Detectors;

use Illuminate\Support\Facades\Log;
class IpQualityScoreDetector extends BaseProxyDetector
{
    protected string $baseUrl = 'https://ipqualityscore.com/api/json/ip/';
    protected ?string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.ipqualityscore.api_key');
    }

    public function getName(): string
    {
        return 'IPQualityScore';
    }

    public function getPriority(): int
    {
        return 3;
    }

    public function getDailyLimit(): int
    {
        return 166; // ~5000/month
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

        $data = $this->makeRequest("{$this->baseUrl}{$this->apiKey}/{$ip}", [
            'strictness' => 0,
            'allow_public_access_points' => true
        ]);

        if (!$data || !isset($data['success']) || !$data['success']) {
            return null;
        }

        $isProxy = $data['proxy'] ?? false;
        $isVpn = $data['vpn'] ?? false;
        $isTor = $data['tor'] ?? false;

        $type = null;
        if ($isTor) $type = 'tor';
        elseif ($isVpn) $type = 'vpn';
        elseif ($isProxy) $type = 'proxy';

        return [
            'is_proxy' => $isProxy || $isVpn || $isTor,
            'type' => $type,
            'confidence' => 95,
            'details' => [
                'fraud_score' => $data['fraud_score'] ?? 0,
                'is_crawler' => $data['is_crawler'] ?? false,
                'recent_abuse' => $data['recent_abuse'] ?? false,
            ]
        ];
    }
}
