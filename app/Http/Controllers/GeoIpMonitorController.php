<?php

namespace App\Http\Controllers;

use App\Services\GeoIpService;
use Illuminate\Http\Request;

class GeoIpMonitorController extends Controller
{
    public function index(GeoIpService $geoIpService)
    {
        $providers = $geoIpService->getProviders();
        $quotaStats = $geoIpService->getQuotaStats();

        return view('admin.geoip-monitor', compact('providers', 'quotaStats'));
    }

    public function testProvider(Request $request, GeoIpService $geoIpService)
    {
        $ip = $request->input('ip', '8.8.8.8');
        $result = $geoIpService->details($ip);

        return response()->json([
            'success' => ! empty($result['country']),
            'data' => $result,
            'providers' => $geoIpService->getProviders(),
        ]);
    }
}
