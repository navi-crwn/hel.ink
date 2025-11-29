<?php

namespace App\Services\ProxyDetection\Detectors;

use App\Services\ProxyDetection\ProxyDetectorInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

abstract class BaseProxyDetector implements ProxyDetectorInterface
{
    protected int $timeout = 5;

    protected function makeRequest(string $url, array $params = []): ?array
    {
        try {
            $response = Http::timeout($this->timeout)->get($url, $params);

            if ($response->successful()) {
                return $response->json();
            }

            Log::debug("{$this->getName()} request failed", [
                'url' => $url,
                'status' => $response->status()
            ]);

            return null;
        } catch (\Throwable $e) {
            Log::debug("{$this->getName()} request error", [
                'url' => $url,
                'error' => $e->getMessage()
            ]);

            return null;
        }
    }

    public function isAvailable(): bool
    {
        return true;
    }
}
