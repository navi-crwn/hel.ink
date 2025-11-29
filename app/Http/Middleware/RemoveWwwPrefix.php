<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RemoveWwwPrefix
{
    public function handle(Request $request, Closure $next): Response
    {
        if (str_starts_with($request->getHost(), 'www.')) {
            return redirect()->to(
                str_replace('www.', '', $request->fullUrl()),
                301
            );
        }

        return $next($request);
    }
}
