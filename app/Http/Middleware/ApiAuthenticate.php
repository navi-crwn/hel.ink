<?php

namespace App\Http\Middleware;

use App\Models\ApiToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get token from Authorization header (Bearer token)
        $authHeader = $request->header('Authorization');
        if (! $authHeader || ! str_starts_with($authHeader, 'Bearer ')) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized',
                'message' => 'Missing or invalid Authorization header. Use: Authorization: Bearer YOUR_TOKEN',
            ], 401);
        }
        // Extract the token
        $plainToken = substr($authHeader, 7); // Remove "Bearer " prefix
        // Find the token in database
        $apiToken = ApiToken::findByPlainToken($plainToken);
        if (! $apiToken) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized',
                'message' => 'Invalid API token',
            ], 401);
        }
        // Check if token is valid (not expired)
        if (! $apiToken->isValid()) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized',
                'message' => 'API token has expired',
            ], 401);
        }
        // Check rate limiting (requests per hour)
        $cacheKey = 'api_rate_limit:'.$apiToken->id;
        $requestCount = Cache::get($cacheKey, 0);
        if ($requestCount >= $apiToken->rate_limit) {
            return response()->json([
                'success' => false,
                'error' => 'Rate Limit Exceeded',
                'message' => "Rate limit of {$apiToken->rate_limit} requests per hour exceeded",
                'retry_after' => Cache::get($cacheKey.':ttl', 3600),
            ], 429);
        }
        // Increment rate limit counter
        if ($requestCount === 0) {
            Cache::put($cacheKey, 1, now()->addHour());
            Cache::put($cacheKey.':ttl', 3600, now()->addHour());
        } else {
            Cache::increment($cacheKey);
        }
        // Mark token as used
        $apiToken->markAsUsed();
        // Attach user and token to request for use in controller
        $request->merge([
            'api_user' => $apiToken->user,
            'api_token' => $apiToken,
        ]);

        return $next($request);
    }
}
