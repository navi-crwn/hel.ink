<?php

namespace App\Http\Controllers;

use App\Models\AbuseReport;
use App\Models\IpBan;
use App\Models\Link;
use App\Models\LinkClick;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function __invoke()
    {
        $stats = [
            'users' => User::count(),
            'links' => Link::count(),
            'clicks' => LinkClick::count(),
            'today_clicks' => LinkClick::whereDate('clicked_at', today())->count(),
        ];

        $recentUsers = User::latest()->limit(5)->get();
        $recentLinks = Link::with('user')->latest()->limit(5)->get();
        $openReports = AbuseReport::orderByDesc('created_at')->limit(5)->get();
        $suspendedUsers = User::where('status', 'suspended')->count();
        $inactiveLinks = Link::where('status', Link::STATUS_INACTIVE)->count();
        $ipBanCount = IpBan::count();
        $queueHeartbeat = Cache::get('queue:heartbeat');

        $activityTrend = LinkClick::selectRaw('DATE(clicked_at) as day, COUNT(*) as total')
            ->where('clicked_at', '>=', now()->subDays(10))
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');

        $topCountries = LinkClick::select('country', DB::raw('COUNT(*) as total'))
            ->whereNotNull('country')
            ->groupBy('country')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view('admin-dashboard', compact(
            'stats',
            'recentUsers',
            'recentLinks',
            'openReports',
            'suspendedUsers',
            'inactiveLinks',
            'ipBanCount',
            'queueHeartbeat',
            'activityTrend',
            'topCountries'
        ));
    }
}
