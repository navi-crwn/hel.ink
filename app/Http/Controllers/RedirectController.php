<?php

namespace App\Http\Controllers;

use App\Jobs\LogLinkClick;
use App\Models\BioPage;
use App\Models\Link;
use App\Services\LinkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class RedirectController extends Controller
{
    public function __construct(private readonly LinkService $links) {}

    public function __invoke(Request $request, string $slug)
    {
        // Check if this is a bio page first
        $bioPage = BioPage::where('slug', $slug)->where('is_published', true)->first();
        if ($bioPage) {
            return redirect()->to('/b/'.$slug);
        }
        $link = Cache::remember($this->links->cacheKey($slug), 300, function () use ($slug) {
            return Link::with(['user'])->where('slug', $slug)->first();
        });
        if (! $link || $link->status !== Link::STATUS_ACTIVE || $link->isExpired()) {
            return $this->notFound($slug);
        }
        if ($link->requiresPassword()) {
            if ($request->isMethod('post')) {
                $request->validate(['password' => ['required', 'string']]);
                if (! Hash::check($request->input('password'), $link->password_hash)) {
                    return back()->withErrors(['password' => 'Incorrect password.']);
                }
            } else {
                return response()->view('link-password', compact('link'));
            }
        }
        $this->logClick($request, $link);
        if ($link->enable_preview && $request->query('skip_preview') !== '1') {
            return response()->view('preview', ['targetUrl' => $link->target_url]);
        }
        if ($this->shouldServePreview($request, $link)) {
            return response()
                ->view('link-preview', compact('link'))
                ->header('Refresh', '0;url='.$link->target_url);
        }

        return redirect()->away($link->target_url, (int) ($link->redirect_type ?? 302));
    }

    protected function logClick(Request $request, Link $link): void
    {
        LogLinkClick::dispatch(
            $link->id,
            $request->ip(),
            $request->headers->get('referer'),
            $request->userAgent()
        );
    }

    protected function shouldServePreview(Request $request, Link $link): bool
    {
        if (! $link->custom_title && ! $link->custom_description && ! $link->custom_image_url) {
            return false;
        }
        $ua = strtolower($request->userAgent() ?? '');
        $bots = ['facebookexternalhit', 'twitterbot', 'slackbot', 'linkedinbot', 'discordbot', 'whatsapp', 'telegrambot', 'skypepreview', 'pinterest'];
        foreach ($bots as $bot) {
            if (str_contains($ua, $bot)) {
                return true;
            }
        }

        return false;
    }

    protected function notFound(?string $slug = null)
    {
        return response()
            ->view('errors.link-missing', ['slug' => $slug], 404);
    }
}
