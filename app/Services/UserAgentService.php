<?php

namespace App\Services;

class UserAgentService
{
    public function parse(?string $userAgent): array
    {
        if (!$userAgent) {
            return [
                'device' => 'Unknown',
                'browser' => 'Unknown',
            ];
        }

        return [
            'device' => $this->detectDevice($userAgent),
            'browser' => $this->detectBrowser($userAgent),
        ];
    }

    private function detectDevice(string $userAgent): string
    {
        if (preg_match('/mobile|android|iphone|ipad|ipod|blackberry|iemobile|opera mini/i', $userAgent)) {
            if (preg_match('/ipad|tablet|kindle/i', $userAgent)) {
                return 'Tablet';
            }
            return 'Mobile';
        }
        
        return 'Desktop';
    }

    private function detectBrowser(string $userAgent): string
    {
        $browsers = [
            'Edg' => 'Edge',
            'OPR|Opera' => 'Opera',
            'Chrome' => 'Chrome',
            'Safari' => 'Safari',
            'Firefox' => 'Firefox',
            'MSIE|Trident' => 'Internet Explorer',
        ];

        foreach ($browsers as $pattern => $browser) {
            if (preg_match("/$pattern/i", $userAgent)) {
                if ($browser === 'Safari' && preg_match('/Chrome|Edg|OPR/i', $userAgent)) {
                    continue;
                }
                return $browser;
            }
        }

        return 'Unknown';
    }
}
