<?php

namespace App\Http\Middleware;

use App\Models\IpBan;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIpBan
{
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $ban = cache()->remember("ip-ban:{$ip}", 60, function () use ($ip) {
            return IpBan::where('ip_address', $ip)->first();
        });
        if ($ban) {
            if ($ban->isExpired()) {
                $ban->delete();
            } else {
                abort(Response::HTTP_FORBIDDEN, 'Your IP is temporarily blocked.');
            }
        }

        return $next($request);
    }
}
