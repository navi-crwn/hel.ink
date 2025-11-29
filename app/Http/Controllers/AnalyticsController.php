<?php

namespace App\Http\Controllers;

use App\Models\LinkClick;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();
        $range = $request->integer('hours', 24);
        $start = now()->subHours($range);
        $countryFilter = $request->input('country', 'all');
        $deviceFilter = $request->input('device', 'all');
        $linkFilter = $request->input('link_id', 'all');

        $clicksQuery = LinkClick::query()
            ->whereHas('link', fn ($q) => $q->where('user_id', $user->id))
            ->where('clicked_at', '>=', $start);
        if ($linkFilter !== 'all') {
            $clicksQuery->where('link_id', $linkFilter);
        }

        if ($countryFilter !== 'all') {
            if ($countryFilter === 'unknown') {
                $clicksQuery->whereNull('country');
            } else {
                $clicksQuery->where('country', $countryFilter);
            }
        }

        if ($deviceFilter !== 'all') {
            $clicksQuery->where(function ($query) use ($deviceFilter) {
                if ($deviceFilter === 'mobile') {
                    $query->where('user_agent', 'like', '%Mobile%');
                } else {
                    $query->where(function ($inner) {
                        $inner->whereNull('user_agent')
                            ->orWhere('user_agent', 'not like', '%Mobile%');
                    });
                }
            });
        }

        $timeline = $clicksQuery
            ->clone()
            ->selectRaw('DATE_FORMAT(clicked_at, "%Y-%m-%d %H:00:00") as bucket, COUNT(*) as total')
            ->groupBy('bucket')
            ->orderBy('bucket')
            ->get()
            ->mapWithKeys(fn ($row) => [
                \Carbon\Carbon::parse($row->bucket)->format('H:i') => (int) $row->total,
            ]);

        $topLinks = $clicksQuery
            ->clone()
            ->selectRaw('link_id, COUNT(*) as total')
            ->groupBy('link_id')
            ->orderByDesc('total')
            ->limit(5)
            ->with('link')
            ->get();

        $topDestinations = $topLinks->groupBy(function ($item) {
            $url = $item->link->target_url ?? '';
            $host = parse_url($url, PHP_URL_HOST);
            return $host ?: 'Unknown';
        })->map(function ($group) {
            return $group->sum('total');
        })->sortDesc();

        $referers = $clicksQuery
            ->clone()
            ->selectRaw('COALESCE(referer, "(direct)") as referer, COUNT(*) as total')
            ->groupBy('referer')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('total', 'referer');

        $countries = $clicksQuery
            ->clone()
            ->selectRaw('COALESCE(country, "Unknown") as country, COUNT(*) as total')
            ->groupBy('country')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('total', 'country');

        $cities = $clicksQuery
            ->clone()
            ->whereNotNull('city')
            ->selectRaw('city, country, COUNT(*) as total')
            ->groupBy('city', 'country')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(fn($item) => [
                'city' => $item->city,
                'country' => $item->country,
                'total' => $item->total
            ]);

        $regions = $clicksQuery
            ->clone()
            ->whereNotNull('region')
            ->selectRaw('region, country, COUNT(*) as total')
            ->groupBy('region', 'country')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(fn($item) => [
                'region' => $item->region,
                'country' => $item->country,
                'total' => $item->total
            ]);

        $isps = $clicksQuery
            ->clone()
            ->whereNotNull('isp')
            ->selectRaw('isp, COUNT(*) as total')
            ->groupBy('isp')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(fn($item) => [
                'isp' => $item->isp,
                'total' => $item->total
            ]);

        $devices = $clicksQuery
            ->clone()
            ->selectRaw('CASE WHEN user_agent LIKE "%Mobile%" THEN "Mobile" ELSE "Desktop" END as device, COUNT(*) as total')
            ->groupBy('device')
            ->pluck('total', 'device');
        $userAgentService = app(\App\Services\UserAgentService::class);
        $clicksForAgents = $clicksQuery->clone()->whereNotNull('user_agent')->take(1000)->get();
        $browserStats = [];
        $osStats = [];
        
        foreach ($clicksForAgents as $click) {
            $parsed = $userAgentService->parse($click->user_agent);
            if (!empty($parsed['browser'])) {
                $browser = $parsed['browser'];
                $browserStats[$browser] = ($browserStats[$browser] ?? 0) + 1;
            }
            if (!empty($parsed['os'])) {
                $os = $parsed['os'];
                $osStats[$os] = ($osStats[$os] ?? 0) + 1;
            }
        }
        
        arsort($browserStats);
        arsort($osStats);
        $browserStats = array_slice($browserStats, 0, 8, true);
        $osStats = array_slice($osStats, 0, 8, true);
        $proxyStats = [
            'total' => $clicksQuery->clone()->where('is_proxy', true)->count(),
            'percentage' => 0,
            'by_type' => $clicksQuery
                ->clone()
                ->where('is_proxy', true)
                ->whereNotNull('proxy_type')
                ->selectRaw('proxy_type, COUNT(*) as total')
                ->groupBy('proxy_type')
                ->pluck('total', 'proxy_type')
                ->toArray()
        ];

        $totalClicks = $clicksQuery->clone()->count();

        if ($totalClicks > 0) {
            $proxyStats['percentage'] = round(($proxyStats['total'] / $totalClicks) * 100, 1);
        }
        $mapData = $clicksQuery
            ->clone()
            ->whereNotNull('country')
            ->selectRaw('country, COUNT(*) as total')
            ->groupBy('country')
            ->get()
            ->pluck('total', 'country')
            ->mapWithKeys(fn($count, $code) => [strtoupper($code) => $count])
            ->toArray();

        $countryCount = $clicksQuery
            ->clone()
            ->selectRaw('COUNT(DISTINCT COALESCE(country, "Unknown")) as total')
            ->value('total') ?? 0;

        $availableCountries = LinkClick::query()
            ->whereHas('link', fn ($q) => $q->where('user_id', $user->id))
            ->selectRaw('COALESCE(country, "Unknown") as label, country')
            ->groupBy('country', 'label')
            ->orderBy('label')
            ->get();

        $availableDevices = collect(['Desktop', 'Mobile']);

        $recentIps = $clicksQuery
            ->clone()
            ->whereNotNull('ip_address')
            ->latest()
            ->limit(20)
            ->get(['ip_address', 'country', 'city', 'created_at']);
        $userLinks = \App\Models\Link::where('user_id', $user->id)->orderBy('created_at', 'desc')->get(['id', 'slug', 'target_url']);

        return view('analytics', compact(
            'topLinks',
            'referers',
            'countries',
            'cities',
            'regions',
            'isps',
            'proxyStats',
            'devices',
            'browserStats',
            'osStats',
            'mapData',
            'range',
            'availableCountries',
            'availableDevices',
            'countryFilter',
            'deviceFilter',
            'topDestinations',
            'timeline',
            'totalClicks',
            'countryCount',
            'userLinks',
            'linkFilter',
            'recentIps'
        ));
    }
}
