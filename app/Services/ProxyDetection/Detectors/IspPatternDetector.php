<?php

namespace App\Services\ProxyDetection\Detectors;
class IspPatternDetector extends BaseProxyDetector
{
    protected array $vpnPatterns = [
        'nordvpn', 'expressvpn', 'surfshark', 'cyberghost', 'private internet access',
        'protonvpn', 'mullvad', 'windscribe', 'tunnelbear', 'hotspot shield',
        'vyprvpn', 'ipvanish', 'purevpn', 'zenmate', 'hola vpn',
        'digitalocean', 'amazon data services', 'aws', 'google cloud',
        'microsoft azure', 'ovh hosting', 'hetzner', 'vultr', 'linode',
        'scaleway', 'contabo', 'choopa', 'quadranet', 'psychz',
        'proxy', 'vpn', 'hosting', 'datacenter', 'data center',
        'cloud', 'server', 'dedicated', 'colocation', 'colo',
        'tor exit', 'tor node', 'relay', 'anonymous',
        'privacy', 'secure connection', 'private network',
    ];

    protected array $knownVpnAsns = [
        'AS396982', // Google Fiber Webpass (used by VPNs)
        'AS16509', // AWS (commonly used by VPNs)
        'AS14061', // DigitalOcean
        'AS20473', // Choopa/Vultr
        'AS24940', // Hetzner
        'AS64050', // Mullvad VPN
    ];

    public function getName(): string
    {
        return 'IspPattern';
    }

    public function getPriority(): int
    {
        return 10; // Lowest priority - fallback only
    }

    public function getDailyLimit(): int
    {
        return PHP_INT_MAX; // Unlimited - no API calls
    }

    public function detect(string $ip): ?array
    {
        $geoIp = app(\App\Services\GeoIpService::class);
        $details = $geoIp->details($ip);
        
        $isp = strtolower($details['provider'] ?? '');
        
        if (empty($isp)) {
            return null;
        }
        $matchedPatterns = [];
        foreach ($this->vpnPatterns as $pattern) {
            if (str_contains($isp, $pattern)) {
                $matchedPatterns[] = $pattern;
            }
        }

        $isProxy = !empty($matchedPatterns);
        $confidence = 60; // Lower confidence for pattern matching
        if (count($matchedPatterns) > 1) {
            $confidence = 75;
        }
        $highConfidencePatterns = ['vpn', 'proxy', 'tor exit', 'nordvpn', 'expressvpn'];
        foreach ($highConfidencePatterns as $pattern) {
            if (str_contains($isp, $pattern)) {
                $confidence = 90;
                break;
            }
        }

        return [
            'is_proxy' => $isProxy,
            'type' => $this->guessType($isp),
            'confidence' => $confidence,
            'details' => [
                'isp' => $details['provider'],
                'matched_patterns' => $matchedPatterns,
            ]
        ];
    }

    protected function guessType(string $isp): ?string
    {
        if (str_contains($isp, 'vpn')) return 'vpn';
        if (str_contains($isp, 'proxy')) return 'proxy';
        if (str_contains($isp, 'tor')) return 'tor';
        if (str_contains($isp, 'datacenter') || str_contains($isp, 'hosting')) return 'datacenter';
        return 'unknown';
    }
}
