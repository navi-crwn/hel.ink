<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureGoogleUserHasPassword
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        // Check if user is Google OAuth user and needs to set password
        // We check if password was set in the last 5 minutes (just registered)
        if ($user && $user->google_id) {
            // If user was created less than 5 minutes ago and coming from Google
            $createdRecently = $user->created_at->diffInMinutes(now()) < 5;
            
            if ($createdRecently && session('google_user_needs_password')) {
                // Redirect to onboarding if not already there
                if (!$request->routeIs('onboarding') && !$request->routeIs('onboarding.set-password') && !$request->routeIs('logout')) {
                    return redirect()->route('onboarding')->with('warning', 'Please set your password to continue.');
                }
            }
        }
        
        return $next($request);
    }
}
