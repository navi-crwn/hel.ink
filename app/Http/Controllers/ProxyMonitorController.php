<?php

namespace App\Http\Controllers;

use App\Services\ProxyDetectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProxyMonitorController extends Controller
{
    protected $proxyService;

    public function __construct(ProxyDetectionService $proxyService)
    {
        $this->proxyService = $proxyService;
    }

    public function index()
    {
        $detectors = $this->proxyService->getDetectors();
        $detectorStats = [];
        foreach ($detectors as $name => $detector) {
            $statsKey = "proxy_detector_stats:{$name}";
            $stats = Cache::get($statsKey, [
                'total_checks' => 0,
                'positive_detections' => 0,
                'errors' => 0,
                'last_check' => null,
            ]);
            $detectorStats[$name] = [
                'enabled' => $detector['enabled'] ?? true,
                'priority' => $detector['priority'] ?? 50,
                'quota' => $detector['quota'] ?? 'Unlimited',
                'stats' => $stats,
                'detection_rate' => $stats['total_checks'] > 0
                    ? round(($stats['positive_detections'] / $stats['total_checks']) * 100, 1)
                    : 0,
            ];
        }

        return view('admin.proxy-monitor', compact('detectorStats'));
    }

    public function test(Request $request)
    {
        $request->validate([
            'ip' => 'required|ip',
        ]);
        $ip = $request->input('ip');
        $result = $this->proxyService->detect($ip);

        return response()->json([
            'success' => true,
            'ip' => $ip,
            'result' => $result,
        ]);
    }

    public function resetStats()
    {
        $detectors = $this->proxyService->getDetectors();
        foreach ($detectors as $name => $detector) {
            $statsKey = "proxy_detector_stats:{$name}";
            Cache::forget($statsKey);
        }

        return redirect()->route('admin.proxy.monitor')
            ->with('success', 'Detector statistics have been reset.');
    }
}
