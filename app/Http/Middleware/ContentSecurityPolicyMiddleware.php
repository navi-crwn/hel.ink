<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        // Allow framing for preview routes (both admin and regular bio preview)
        $isPreviewRoute = $request->routeIs('admin.bio.preview') || $request->routeIs('bio.preview');
        $csp = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://challenges.cloudflare.com https://fonts.bunny.net https://fonts.googleapis.com https://cdn.jsdelivr.net https://code.jquery.com https://unpkg.com https://cdn.tailwindcss.com https://static.cloudflareinsights.com https://www.googletagmanager.com https://www.google-analytics.com https://connect.facebook.net",
            "style-src 'self' 'unsafe-inline' https://fonts.bunny.net https://fonts.googleapis.com https://cdn.jsdelivr.net https://unpkg.com",
            "img-src 'self' data: https: blob: https://flagcdn.com https://*.tile.openstreetmap.org https://www.google-analytics.com https://www.facebook.com",
            "font-src 'self' https://fonts.bunny.net https://fonts.gstatic.com data:",
            "connect-src 'self' data: blob: https://challenges.cloudflare.com https://raw.githubusercontent.com https://*.tile.openstreetmap.org https://cdn.jsdelivr.net https://unpkg.com https://www.google-analytics.com https://analytics.google.com https://www.facebook.com",
            "frame-src 'self' https://challenges.cloudflare.com https://maps.google.com https://www.google.com https://www.youtube.com https://youtube.com https://open.spotify.com https://w.soundcloud.com",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            $isPreviewRoute ? "frame-ancestors 'self'" : "frame-ancestors 'none'",
            'upgrade-insecure-requests',
        ];
        $response->headers->set('Content-Security-Policy', implode('; ', $csp));
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', $isPreviewRoute ? 'SAMEORIGIN' : 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        return $response;
    }
}
