<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShortenLinkRequest;
use App\Models\IpWatchlist;
use App\Models\Link;
use App\Services\LinkService;
use App\Services\TurnstileService;

class PublicLinkController extends Controller
{
    use HandlesSlugs, ScreensLinks;

    public function __construct(
        private readonly TurnstileService $turnstile,
        private readonly LinkService $links
    ) {}

    public function index()
    {
        return view('landing', [
            'shortened' => session('shortened_link'),
        ]);
    }

    public function store(ShortenLinkRequest $request)
    {
        if (! $this->turnstile->verify($request->input('cf-turnstile-response'), $request->ip())) {
            return back()->withErrors(['turnstile' => 'Security verification failed. Please try again.'])->withInput();
        }
        $data = $request->validated();
        $verdict = $this->screenLink($data['target_url'], $data['slug'] ?? null, $request->ip(), 'public');
        if ($verdict['action'] === \App\Services\LinkScreeningService::BLOCK) {
            return back()
                ->withErrors(['target_url' => 'This link cannot be shortened because it was flagged by our safety filters.'])
                ->withInput();
        }
        $watchlistEntry = IpWatchlist::where('ip_address', $request->ip())->first();
        if ($watchlistEntry) {
            $watchlistEntry->increment('attempt_count');
            $watchlistEntry->update([
                'last_attempt_at' => now(),
                'reason' => ($watchlistEntry->reason ?? '').' | Used public link creation on '.now()->format('Y-m-d H:i:s'),
            ]);
        }
        $link = Link::create([
            'target_url' => $data['target_url'],
            'slug' => $this->prepareSlug($data['slug'] ?? null),
            'status' => Link::STATUS_ACTIVE,
            'created_ip' => $request->ip(),
            ...$this->screeningAttributes($verdict),
        ]);
        $this->links->generateQr($link);
        $this->links->forgetCache($link);

        return redirect()
            ->route('home')
            ->with('shortened_link', $link);
    }
}
