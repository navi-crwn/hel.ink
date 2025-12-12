<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AutoLogout
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $timeout = config('session.inactivity_logout', 60);
            $lastActivity = session('last_activity_time');
            if ($lastActivity && now()->diffInMinutes(\Illuminate\Support\Carbon::parse($lastActivity)) >= $timeout) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->with('status', 'For security, you have been logged out after a period of inactivity.');
            }
            session(['last_activity_time' => now()]);
        }

        return $next($request);
    }
}
