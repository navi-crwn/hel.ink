<?php

namespace App\Http\Controllers;

use App\Models\BioPage;
use App\Models\BioLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Jenssegers\Agent\Agent;

class PublicBioController extends Controller
{
    public function show(Request $request, string $slug)
    {
        $bioPage = BioPage::with(['links' => function ($query) {
            $query->active()->ordered();
        }])
            ->where('slug', $slug)
            ->first();

        // Page not found or not published
        if (!$bioPage) {
            return response()->view('bio.not-found', ['slug' => $slug], 404);
        }
        
        if (!$bioPage->is_published) {
            return response()->view('bio.not-found', ['slug' => $slug, 'unpublished' => true], 404);
        }

        // Password protection check
        if ($bioPage->password_enabled && $bioPage->password) {
            $sessionKey = "bio_unlocked_{$bioPage->id}";
            
            if (!session($sessionKey)) {
                if ($request->isMethod('post') && $request->has('password')) {
                    if ($request->input('password') === $bioPage->password) {
                        session([$sessionKey => true]);
                        // Use 303 redirect (See Other) to convert POST to GET - prevents browser resubmit warning
                        return redirect()->to(url()->current(), 303);
                    } else {
                        return view('bio.password', compact('bioPage'))->withErrors(['password' => 'Incorrect password']);
                    }
                } else {
                    return view('bio.password', compact('bioPage'));
                }
            }
        }

        // 18+ Age verification check
        if ($bioPage->is_adult_content) {
            $sessionKey = "bio_age_verified_{$bioPage->id}";
            
            if (!session($sessionKey)) {
                if ($request->isMethod('post') && $request->input('age_confirm') === 'yes') {
                    session([$sessionKey => true]);
                    // Use 303 redirect (See Other) to convert POST to GET - prevents browser resubmit warning
                    return redirect()->to(url()->current(), 303);
                } else {
                    return view('bio.age-verify', compact('bioPage'));
                }
            }
        }

        $this->trackView($bioPage);

        return view('bio.show', compact('bioPage'));
    }

    public function click(BioLink $bioLink)
    {
        $ipAddress = request()->ip();
        $agent = new Agent();

        $clickData = [
            'ip_address' => $ipAddress,
            'device' => $this->getDeviceType($agent),
            'browser' => $agent->browser(),
            'referrer' => request()->header('referer'),
            'country' => $this->getCountryFromIp($ipAddress),
        ];

        $bioLink->recordClick($clickData);

        return redirect($bioLink->url);
    }

    public function qr(string $slug)
    {
        $bioPage = BioPage::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $url = url('/b/' . $bioPage->slug);

        $qrCode = QrCode::format('png')
            ->size(512)
            ->margin(2)
            ->errorCorrection('H')
            ->generate($url);

        return response($qrCode)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', "attachment; filename=\"{$bioPage->slug}-qr.png\"");
    }

    protected function trackView(BioPage $bioPage): void
    {
        $ipAddress = request()->ip();
        $cacheKey = "bio_view:{$bioPage->id}:{$ipAddress}";

        if (!Cache::has($cacheKey)) {
            $bioPage->incrementViews();
            Cache::put($cacheKey, true, now()->addDay());
        }
    }

    protected function getDeviceType(Agent $agent): string
    {
        if ($agent->isDesktop()) {
            return 'desktop';
        } elseif ($agent->isTablet()) {
            return 'tablet';
        } elseif ($agent->isMobile()) {
            return 'mobile';
        }

        return 'unknown';
    }

    protected function getCountryFromIp(string $ipAddress): ?string
    {
        try {
            $ipService = app(\App\Services\IpLocationService::class);
            $data = $ipService->locate($ipAddress);
            
            return $data['country_code'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
