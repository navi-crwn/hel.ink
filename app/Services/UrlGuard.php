<?php

namespace App\Services;

/**
 * Guards server-side (outbound) HTTP fetches against SSRF.
 *
 * A URL is only considered safe when it uses http/https and every IP its
 * host resolves to is a publicly routable address. This blocks requests to
 * loopback, private, link-local and other reserved ranges — including cloud
 * metadata endpoints such as 169.254.169.254.
 */
class UrlGuard
{
    /**
     * Determine whether the given URL is safe to fetch from the server.
     */
    public function isSafe(?string $url): bool
    {
        if (! is_string($url) || $url === '') {
            return false;
        }
        $parts = parse_url($url);
        if ($parts === false || empty($parts['scheme']) || empty($parts['host'])) {
            return false;
        }
        $scheme = strtolower($parts['scheme']);
        if (! in_array($scheme, ['http', 'https'], true)) {
            return false;
        }
        $host = $parts['host'];
        // Strip IPv6 brackets, e.g. [::1]
        $host = trim($host, '[]');
        $ips = $this->resolveHost($host);
        if ($ips === []) {
            return false;
        }
        foreach ($ips as $ip) {
            if (! $this->isPublicIp($ip)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Resolve a host (or IP literal) to a list of IP addresses.
     *
     * @return array<int, string>
     */
    protected function resolveHost(string $host): array
    {
        if (filter_var($host, FILTER_VALIDATE_IP)) {
            return [$host];
        }
        $ips = [];
        $records = @dns_get_record($host, DNS_A + DNS_AAAA);
        if (is_array($records)) {
            foreach ($records as $record) {
                if (! empty($record['ip'])) {
                    $ips[] = $record['ip'];
                }
                if (! empty($record['ipv6'])) {
                    $ips[] = $record['ipv6'];
                }
            }
        }
        if ($ips === []) {
            $resolved = gethostbyname($host);
            if ($resolved !== $host && filter_var($resolved, FILTER_VALIDATE_IP)) {
                $ips[] = $resolved;
            }
        }

        return $ips;
    }

    /**
     * Whether an IP is a publicly routable address (not private/reserved).
     */
    protected function isPublicIp(string $ip): bool
    {
        return (bool) filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        );
    }
}
