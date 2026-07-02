<?php

namespace App\Services;

use App\Models\DomainBlacklist;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

/**
 * Screens destination URLs at link-creation time to keep the shortener from
 * silently distributing illegal or malicious content.
 *
 * Layered checks, cheapest first:
 *   1. Admin domain blacklist          -> hard block
 *   2. Google Safe Browsing (optional) -> hard block on known threats
 *   3. Keyword heuristic               -> flag for review (or block)
 *
 * Returns a verdict: ['action' => 'allow'|'flag'|'block', 'reason' => ?string].
 */
class LinkScreeningService
{
    public const ALLOW = 'allow';

    public const FLAG = 'flag';

    public const BLOCK = 'block';

    public function screen(string $url, ?string $slug = null): array
    {
        if (! config('abuse.enabled', true)) {
            return $this->verdict(self::ALLOW);
        }

        // 1. Admin-curated domain blacklist.
        $blocked = DomainBlacklist::isBlocked($url);
        if ($blocked) {
            $category = $blocked['category'] ? ' ('.$blocked['category'].')' : '';
            return $this->verdict(self::BLOCK, 'Blacklisted domain'.$category);
        }

        // 2. Google Safe Browsing (only if configured).
        if ($threat = $this->safeBrowsingThreat($url)) {
            return $this->verdict(self::BLOCK, 'Safe Browsing: '.$threat);
        }

        // 3. Keyword heuristic.
        if ($keyword = $this->keywordHit($url, $slug)) {
            $action = config('abuse.heuristic_action', 'flag') === 'block'
                ? self::BLOCK
                : self::FLAG;

            return $this->verdict($action, 'Matched restricted keyword: '.$keyword);
        }

        return $this->verdict(self::ALLOW);
    }

    protected function verdict(string $action, ?string $reason = null): array
    {
        return ['action' => $action, 'reason' => $reason];
    }

    /**
     * Query Google Safe Browsing. Returns the matched threat type, or null when
     * disabled, clean, or on any error (fail-open so screening never blocks
     * legitimate creation because of an upstream outage).
     */
    protected function safeBrowsingThreat(string $url): ?string
    {
        $key = config('abuse.safe_browsing.api_key');
        if (! $key) {
            return null;
        }
        try {
            $client = new Client(['timeout' => 4]);
            $response = $client->post(config('abuse.safe_browsing.endpoint'), [
                'query' => ['key' => $key],
                'json' => [
                    'client' => ['clientId' => config('app.name', 'hel.ink'), 'clientVersion' => '1.0'],
                    'threatInfo' => [
                        'threatTypes' => config('abuse.safe_browsing.threat_types'),
                        'platformTypes' => ['ANY_PLATFORM'],
                        'threatEntryTypes' => ['URL'],
                        'threatEntries' => [['url' => $url]],
                    ],
                ],
            ]);
            $body = json_decode((string) $response->getBody(), true);
            if (! empty($body['matches'][0]['threatType'])) {
                return $body['matches'][0]['threatType'];
            }
        } catch (\Throwable $e) {
            Log::warning('Safe Browsing lookup failed', ['error' => $e->getMessage()]);
        }

        return null;
    }

    /**
     * Whole-word, case-insensitive keyword match against the URL and slug.
     * Returns the first matched keyword, or null.
     */
    protected function keywordHit(string $url, ?string $slug): ?string
    {
        $keywords = config('abuse.keywords', []);
        if (empty($keywords)) {
            return null;
        }
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));
        $host = preg_replace('/^www\./', '', $host);
        foreach (config('abuse.keyword_allowlist_hosts', []) as $allowed) {
            if ($host !== '' && strtolower($allowed) === $host) {
                return null;
            }
        }
        $haystack = strtolower($url.' '.($slug ?? ''));
        // Normalise separators so "child_porn"/"child.porn" collapse to spaces.
        $normalized = preg_replace('/[^a-z0-9]+/', ' ', $haystack);
        foreach ($keywords as $keyword) {
            $needle = strtolower(trim($keyword));
            if ($needle === '') {
                continue;
            }
            // Match the keyword as a whole token (optional plural "s") in either
            // the raw or normalised form.
            $pattern = '/(?:^|[^a-z0-9])'.preg_quote($needle, '/').'s?(?:$|[^a-z0-9])/i';
            $needleSpaced = preg_replace('/[^a-z0-9]+/', ' ', $needle);
            if (preg_match($pattern, $haystack)
                || str_contains($normalized, ' '.$needleSpaced.' ')
                || str_contains($normalized, ' '.$needleSpaced.'s ')
                || str_starts_with($normalized.' ', $needleSpaced.' ')
                || str_starts_with($normalized.' ', $needleSpaced.'s ')) {
                return $keyword;
            }
        }

        return null;
    }
}
