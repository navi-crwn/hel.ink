<?php

namespace App\Http\Controllers;

use App\Services\LinkScreeningService;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;

/**
 * Shared link-abuse screening for the controllers that create short links.
 */
trait ScreensLinks
{
    /**
     * Screen a destination URL and, on a block/flag verdict, alert the
     * security address so takedown does not depend on live monitoring.
     *
     * @return array{action: string, reason: ?string}
     */
    protected function screenLink(string $url, ?string $slug, string $ip, string $context): array
    {
        $verdict = app(LinkScreeningService::class)->screen($url, $slug);
        if ($verdict['action'] === LinkScreeningService::ALLOW) {
            return $verdict;
        }
        Log::warning('Link screening triggered', [
            'action' => $verdict['action'],
            'reason' => $verdict['reason'],
            'url' => $url,
            'slug' => $slug,
            'ip' => $ip,
            'context' => $context,
        ]);
        if (config('abuse.notify_on_flag', true)) {
            try {
                app(NotificationService::class)->notifySuspiciousActivity([
                    'type' => 'Link auto-'.$verdict['action'],
                    'reason' => $verdict['reason'],
                    'target_url' => $url,
                    'slug' => $slug,
                    'ip' => $ip,
                    'source' => $context,
                    'detected_at' => now()->toDateTimeString(),
                ]);
            } catch (\Throwable $e) {
                Log::error('Failed to send link screening alert', ['error' => $e->getMessage()]);
            }
        }

        return $verdict;
    }

    /**
     * Attributes to persist on a link given a screening verdict, so a flagged
     * link is created in a non-redirecting review state with an audit trail.
     *
     * @return array<string, mixed>
     */
    protected function screeningAttributes(array $verdict): array
    {
        if ($verdict['action'] === LinkScreeningService::FLAG) {
            return [
                'status' => \App\Models\Link::STATUS_FLAGGED,
                'flagged_at' => now(),
                'flag_reason' => $verdict['reason'],
            ];
        }

        return [];
    }
}
