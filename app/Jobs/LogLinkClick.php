<?php

namespace App\Jobs;

use App\Models\Link;
use App\Models\LinkClick;
use App\Services\GeoIpService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LogLinkClick implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected int $linkId,
        protected string $ip,
        protected ?string $referer,
        protected ?string $userAgent
    ) {}

    public function handle(GeoIpService $geoIp): void
    {
        $link = Link::find($this->linkId);
        if (! $link) {
            return;
        }
        try {
            $geoData = $geoIp->details($this->ip);
            $proxyService = app(\App\Services\ProxyDetectionService::class);
            $proxyData = $proxyService->detect($this->ip);
            LinkClick::create([
                'link_id' => $link->id,
                'referer' => $this->referer,
                'ip_address' => $this->ip,
                'country' => $geoData['country'],
                'country_name' => $geoData['country_name'],
                'city' => $geoData['city'],
                'region' => $geoData['region'],
                'isp' => $geoData['provider'],
                'is_proxy' => $proxyData['is_proxy'],
                'proxy_type' => $proxyData['type'],
                'proxy_confidence' => $proxyData['confidence'],
                'user_agent' => substr($this->userAgent ?? 'unknown', 0, 255),
                'clicked_at' => now(),
            ]);
            $link->increment('clicks');
            $link->last_clicked_at = now();
            $link->saveQuietly();
            Cache::put('queue:heartbeat', now()->toDateTimeString(), now()->addMinutes(5));
        } catch (\Throwable $e) {
            Log::warning('LogLinkClick failed', ['link_id' => $link->id, 'error' => $e->getMessage()]);
        }
    }
}
