<?php

namespace App\Http\Controllers;

use App\Models\AbuseReport;
use App\Models\DomainBlacklist;
use App\Models\Link;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminAbuseController extends Controller
{
    public function index(): View
    {
        $reports = AbuseReport::latest()->paginate(20);

        return view('admin-abuse', compact('reports'));
    }

    public function update(Request $request, AbuseReport $report): RedirectResponse
    {
        $report->update([
            'status' => $request->input('status', 'open'),
        ]);
        $messages = ['Report updated.'];

        // One-click takedown: disable the reported short link immediately.
        if ($request->boolean('takedown') && $report->slug) {
            $link = Link::where('slug', $report->slug)->first();
            if ($link) {
                $link->update([
                    'status' => Link::STATUS_INACTIVE,
                    'flagged_at' => now(),
                    'flag_reason' => 'Disabled via abuse report #'.$report->id,
                ]);
                app(\App\Services\LinkService::class)->forgetCache($link);
                $messages[] = 'Link "'.$report->slug.'" disabled.';
            } else {
                $messages[] = 'No link found for slug "'.$report->slug.'".';
            }
        }

        // Optionally blacklist the reported destination domain.
        if ($request->boolean('blacklist_domain')) {
            $host = parse_url((string) $report->url, PHP_URL_HOST);
            if ($host) {
                $host = preg_replace('/^www\./', '', strtolower($host));
                DomainBlacklist::firstOrCreate(
                    ['domain' => $host, 'match_type' => 'exact'],
                    ['category' => 'abuse', 'notes' => 'Added from abuse report #'.$report->id]
                );
                $messages[] = 'Domain "'.$host.'" blacklisted.';
            }
        }

        return back()->with('status', implode(' ', $messages));
    }
}
