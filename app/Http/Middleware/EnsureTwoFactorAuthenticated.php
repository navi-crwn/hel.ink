<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user && 
            $user->two_factor_secret && 
            $user->two_factor_confirmed_at && 
            !$request->session()->get('2fa_verified', false)) {
            if (!$request->is('two-factor-challenge') && !$request->is('two-factor-challenge/*')) {
                $request->session()->put('url.intended', $request->fullUrl());
            }
            
            return redirect()->route('two-factor.show');
        }

        return $next($request);
    }
}
