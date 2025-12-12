<?php

namespace App\Http;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user || ! $user->isAdmin()) {
            abort(403);
        }
        if ($user->isSuspended()) {
            auth()->logout();
            abort(403, 'Account suspended.');
        }

        return $next($request);
    }
}
