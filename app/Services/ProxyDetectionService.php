<?php

namespace App\Services;

use App\Services\ProxyDetection\Detectors\ProxyCheckDetector;
use App\Services\ProxyDetection\Detectors\IpHubDetector;
use App\Services\ProxyDetection\Detectors\IpQualityScoreDetector;
use App\Services\ProxyDetection\Detectors\VpnApiDetector;
use App\Services\ProxyDetection\Detectors\IspPatternDetector;
use App\Services\GeoIP\QuotaManager;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProxyDetectionService
{
    protected array $detectors = [];
    protected QuotaManager $quotaManager;

    public function __construct()
    {
        $this->quotaManager = new QuotaManager();
        $this->detectors = [
            new ProxyCheckDetector(),
            new IpHubDetector(),
            new IpQualityScoreDetector(),
            new VpnApiDetector(),
            new IspPatternDetector(), // Fallback - no API needed
        ];
        usort($this->detectors, fn($a, $b) => $a->getPriority() <=> $b->getPriority());
    }
    public function detect(string $ip): array
    {
        $cacheKey = "proxy:detection:{$ip}";
        $cached = Cache::get($cacheKey);
        
        if ($cached !== null) {
            return $cached;
        }

        $results = [];
        $votesProxy = 0;
        $votesClean = 0;
        $totalConfidence = 0;
        $detectorCount = 0;
        $types = [];
        $allDetails = [];
        foreach ($this->detectors as $detector) {
            if (!$detector->isAvailable()) {
                continue;
            }

            $detectorName = $detector->getName();
            if ($detector->getDailyLimit() < PHP_INT_MAX) {
                $usage = $this->quotaManager->getUsage($detectorName, 'daily');
                if ($usage >= $detector->getDailyLimit()) {
                    Log::debug("Proxy detector quota exceeded", ['detector' => $detectorName]);
                    continue;
                }
            }
            $result = $detector->detect($ip);

            if ($result === null) {
                continue;
            }
            if ($detector->getDailyLimit() < PHP_INT_MAX) {
                $this->quotaManager->increment($detectorName);
            }

            $detectorCount++;
            $results[$detectorName] = $result;
            if ($result['is_proxy']) {
                $votesProxy += $result['confidence'] / 100;
                if ($result['type']) {
                    $types[] = $result['type'];
                }
            } else {
                $votesClean += $result['confidence'] / 100;
            }

            $totalConfidence += $result['confidence'];
            $allDetails[$detectorName] = $result['details'] ?? [];

            Log::info("Proxy detection result", [
                'ip' => $ip,
                'detector' => $detectorName,
                'is_proxy' => $result['is_proxy'],
                'type' => $result['type'],
                'confidence' => $result['confidence']
            ]);
        }
        if ($detectorCount === 0) {
            $finalResult = [
                'is_proxy' => false,
                'type' => null,
                'confidence' => 0,
                'consensus' => 'unknown',
                'detectors_used' => 0,
                'details' => []
            ];
            Cache::put($cacheKey, $finalResult, now()->addHour());
            return $finalResult;
        }
        $avgConfidence = $totalConfidence / $detectorCount;
        $isProxy = $votesProxy > $votesClean;
        $type = null;
        if (!empty($types)) {
            $typeCounts = array_count_values($types);
            arsort($typeCounts);
            $type = array_key_first($typeCounts);
        }
        $consensusRatio = $detectorCount > 1 
            ? max($votesProxy, $votesClean) / ($votesProxy + $votesClean)
            : 1.0;

        $consensus = 'unknown';
        if ($consensusRatio >= 0.8) {
            $consensus = 'strong';
        } elseif ($consensusRatio >= 0.6) {
            $consensus = 'moderate';
        } else {
            $consensus = 'weak';
        }

        $finalResult = [
            'is_proxy' => $isProxy,
            'type' => $type,
            'confidence' => round($avgConfidence),
            'consensus' => $consensus,
            'votes' => [
                'proxy' => round($votesProxy, 2),
                'clean' => round($votesClean, 2),
            ],
            'detectors_used' => $detectorCount,
            'detector_results' => $results,
            'details' => $allDetails,
        ];
        Cache::put($cacheKey, $finalResult, now()->addDay());

        return $finalResult;
    }
    public function isProxy(string $ip): bool
    {
        $result = $this->detect($ip);
        return $result['is_proxy'];
    }
    public function getStats(): array
    {
        $stats = [];
        
        foreach ($this->detectors as $detector) {
            $name = $detector->getName();
            $stats[$name] = [
                'available' => $detector->isAvailable(),
                'priority' => $detector->getPriority(),
                'daily_limit' => $detector->getDailyLimit(),
                'daily_usage' => $this->quotaManager->getUsage($name, 'daily'),
            ];
        }

        return $stats;
    }
    public function getDetectors(): array
    {
        return array_map(function($detector) {
            $name = $detector->getName();
            return [
                'name' => $name,
                'priority' => $detector->getPriority(),
                'available' => $detector->isAvailable(),
                'daily_limit' => $detector->getDailyLimit(),
                'daily_usage' => $this->quotaManager->getUsage($name, 'daily'),
            ];
        }, $this->detectors);
    }
}
