<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\IpWatchlist;
use App\Models\LoginHistory;
use App\Models\User;
use App\Services\GeoIpService;
use App\Services\TurnstileService;
use App\Services\UserAgentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function __construct(
        private readonly TurnstileService $turnstile,
        private readonly GeoIpService $geoIp,
        private readonly UserAgentService $userAgent
    ) {
    }

    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        if (! app()->runningUnitTests() && env('APP_ENV') !== 'testing') {
            if (! $this->turnstile->verify($request->input('cf-turnstile-response'), $request->ip())) {
                return back()
                    ->withErrors(['turnstile' => 'Security verification failed, please try again.'])
                    ->onlyInput('email');
            }
        }
        $user = User::where('email', $request->email)->first();
        if ($user && $user->status === User::STATUS_SUSPENDED) {
            IpWatchlist::addOrUpdate(
                $request->ip(),
                $user->id,
                'Login attempt by suspended user: ' . $user->email
            );
            
            return redirect()->route('suspended')
                ->with('email', $request->email);
        }

        $request->authenticate();
        $request->session()->regenerate();

        $user = $request->user();
        if ($user) {
            $geoDetails = $this->geoIp->details($request->ip());
            $uaDetails = $this->userAgent->parse($request->userAgent());
            $proxyService = app(\App\Services\ProxyDetectionService::class);
            $proxyResult = $proxyService->detect($request->ip());
            $user->forceFill([
                'last_login_ip' => $request->ip(),
                'last_login_at' => now(),
                'last_login_country' => $geoDetails['country'],
                'last_login_city' => $geoDetails['city'],
                'last_login_provider' => $geoDetails['provider'],
                'last_login_device' => $uaDetails['device'],
                'last_login_browser' => $uaDetails['browser'],
            ])->saveQuietly();
            LoginHistory::create([
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'country' => $geoDetails['country'],
                'country_name' => $geoDetails['country_name'] ?? null,
                'city' => $geoDetails['city'],
                'region' => $geoDetails['region'] ?? null,
                'provider' => $geoDetails['provider'],
                'isp' => $geoDetails['isp'] ?? null,
                'device' => $uaDetails['device'],
                'browser' => $uaDetails['browser'],
                'is_proxy' => $proxyResult['is_proxy'],
                'proxy_type' => $proxyResult['type'],
                'proxy_confidence' => $proxyResult['confidence'],
                'logged_in_at' => now(),
            ]);
            $oldHistories = LoginHistory::where('user_id', $user->id)
                ->orderBy('logged_in_at', 'desc')
                ->skip(5)
                ->take(1000)
                ->pluck('id');
            
            if ($oldHistories->isNotEmpty()) {
                LoginHistory::whereIn('id', $oldHistories)->delete();
            }
            if (!$user->two_factor_secret || !$user->two_factor_confirmed_at) {
                $request->session()->put('2fa_verified', true);
            }

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'superadmin') {
                return redirect()->route('superadmin.dashboard');
            }
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->forget('2fa_verified');

        return redirect('/');
    }
}
