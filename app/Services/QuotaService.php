<?php

namespace App\Services;

use App\Models\Link;
use Illuminate\Support\Facades\Cache;

class QuotaService
{
    public function incrementIpUsage(string $ip): array
    {
        $minuteKey = "quota:ip:minute:{$ip}:" . now()->format('YmdHi');
        $dayKey = "quota:ip:day:{$ip}:" . now()->format('Ymd');

        $minute = Cache::increment($minuteKey);
        $day = Cache::increment($dayKey);

        Cache::put($minuteKey, $minute, now()->addMinute());
        Cache::put($dayKey, $day, now()->endOfDay());

        return [$minute, $day];
    }

    public function checkGuestLimits(string $ip): bool
    {
        $limits = config('limits');
        [$perMinute, $perDay] = $this->incrementIpUsage($ip);

        if ($perMinute > $limits['ip']['creates_per_minute'] || $perDay > $limits['ip']['creates_per_day']) {
            return false;
        }

        $todayKey = "quota:guest:day:{$ip}:" . now()->format('Ymd');
        $totalKey = "quota:guest:total:{$ip}";

        $today = Cache::increment($todayKey);
        $total = Cache::increment($totalKey);

        Cache::put($todayKey, $today, now()->endOfDay());
        Cache::put($totalKey, $total, now()->addDays(7));

        if ($today > $limits['guest']['max_links_per_day'] || $total > $limits['guest']['max_total_links']) {
            return false;
        }

        return true;
    }

    public function checkUserLimits(int $userId): bool
    {
        $limits = config('limits.user');

        $dayCount = Link::where('user_id', $userId)
            ->whereDate('created_at', today())
            ->count();

        $activeCount = Link::where('user_id', $userId)
            ->where('status', Link::STATUS_ACTIVE)
            ->count();

        return $dayCount < $limits['max_links_per_day'] && $activeCount < $limits['max_active_links'];
    }
}
