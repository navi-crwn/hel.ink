<?php

namespace App\Http\Controllers;

use App\Models\BioPage;
use App\Models\BioLink;
use App\Models\BioClick;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminBioController extends Controller
{
    /**
     * Bio Monitoring Dashboard - Overview analytics for all bio pages
     */
    public function monitor(Request $request)
    {
        $period = $request->get('period', '30'); // days
        $startDate = now()->subDays((int)$period);

        // Overall Statistics
        $stats = [
            'total_bio_pages' => BioPage::count(),
            'published' => BioPage::where('is_published', true)->count(),
            'draft' => BioPage::where('is_published', false)->count(),
            'total_views' => BioPage::sum('view_count'),
            'total_links' => BioLink::count(),
            'active_links' => BioLink::where('is_active', true)->count(),
            'total_clicks' => BioClick::count(),
            'unique_users' => BioPage::distinct('user_id')->count('user_id'),
        ];

        // Period Statistics
        $periodStats = [
            'new_bio_pages' => BioPage::where('created_at', '>=', $startDate)->count(),
            'new_links' => BioLink::where('created_at', '>=', $startDate)->count(),
            'period_clicks' => BioClick::where('created_at', '>=', $startDate)->count(),
        ];

        // Daily Views/Clicks Chart Data (last N days)
        $dailyData = BioClick::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as clicks')
            )
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Fill in missing dates
        $chartLabels = [];
        $chartClicks = [];
        for ($i = (int)$period - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('M d');
            $chartClicks[] = $dailyData[$date]->clicks ?? 0;
        }

        // Top Performing Bio Pages
        $topBioPages = BioPage::with('user')
            ->withCount('links')
            ->orderByDesc('view_count')
            ->limit(10)
            ->get();

        // Top Performing Links
        $topLinks = BioLink::with(['bioPage.user'])
            ->where('click_count', '>', 0)
            ->orderByDesc('click_count')
            ->limit(10)
            ->get();

        // Recent Activity (latest clicks)
        $recentClicks = BioClick::with(['bioLink.bioPage.user'])
            ->latest()
            ->limit(20)
            ->get();

        // Bio Pages by User (top creators)
        $topCreators = User::select('users.*')
            ->withCount('bioPages')
            ->having('bio_pages_count', '>', 0)
            ->orderByDesc('bio_pages_count')
            ->limit(10)
            ->get();

        // Clicks by Country
        $clicksByCountry = BioClick::select('country', DB::raw('COUNT(*) as count'))
            ->whereNotNull('country')
            ->where('country', '!=', '')
            ->where('created_at', '>=', $startDate)
            ->groupBy('country')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // Clicks by Device
        $clicksByDevice = BioClick::select('device', DB::raw('COUNT(*) as count'))
            ->whereNotNull('device')
            ->where('device', '!=', '')
            ->where('created_at', '>=', $startDate)
            ->groupBy('device')
            ->orderByDesc('count')
            ->get();

        // Clicks by Browser
        $clicksByBrowser = BioClick::select('browser', DB::raw('COUNT(*) as count'))
            ->whereNotNull('browser')
            ->where('browser', '!=', '')
            ->where('created_at', '>=', $startDate)
            ->groupBy('browser')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // Recent Bio Pages
        $recentBioPages = BioPage::with('user')
            ->withCount('links')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.bio.monitor', compact(
            'stats',
            'periodStats',
            'chartLabels',
            'chartClicks',
            'topBioPages',
            'topLinks',
            'recentClicks',
            'topCreators',
            'clicksByCountry',
            'clicksByDevice',
            'clicksByBrowser',
            'recentBioPages',
            'period'
        ));
    }

    /**
     * Export bio data as CSV
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'bio_pages');
        $filename = "bio_{$type}_" . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        if ($type === 'bio_pages') {
            $data = BioPage::with('user')->get();
            $callback = function() use ($data) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['ID', 'Slug', 'Title', 'User', 'Email', 'Views', 'Links Count', 'Published', 'Created At']);
                
                foreach ($data as $bio) {
                    fputcsv($file, [
                        $bio->id,
                        $bio->slug,
                        $bio->title,
                        $bio->user->name ?? 'N/A',
                        $bio->user->email ?? 'N/A',
                        $bio->view_count,
                        $bio->links()->count(),
                        $bio->is_published ? 'Yes' : 'No',
                        $bio->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
                fclose($file);
            };
        } elseif ($type === 'clicks') {
            $data = BioClick::with(['bioLink.bioPage'])->latest()->limit(10000)->get();
            $callback = function() use ($data) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['ID', 'Bio Page', 'Link Title', 'Country', 'Device', 'Browser', 'Referrer', 'Clicked At']);
                
                foreach ($data as $click) {
                    fputcsv($file, [
                        $click->id,
                        $click->bioLink->bioPage->title ?? 'N/A',
                        $click->bioLink->title ?? 'N/A',
                        $click->country,
                        $click->device,
                        $click->browser,
                        $click->referrer,
                        $click->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
                fclose($file);
            };
        } else {
            return back()->with('error', 'Invalid export type');
        }

        return response()->stream($callback, 200, $headers);
    }

    public function index(Request $request)
    {
        $query = BioPage::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('slug', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        $bioPages = $query->latest()->paginate(20);

        $stats = [
            'total' => BioPage::count(),
            'published' => BioPage::where('is_published', true)->count(),
            'draft' => BioPage::where('is_published', false)->count(),
            'total_views' => BioPage::sum('view_count'),
        ];

        return view('admin.bio.index', compact('bioPages', 'stats'));
    }

    public function show(BioPage $bioPage)
    {
        $bioPage->load(['user', 'links', 'clicks' => function ($query) {
            $query->latest()->limit(50);
        }]);

        $recentClicks = $bioPage->clicks;

        return view('admin.bio.show', compact('bioPage', 'recentClicks'));
    }

    public function destroy(BioPage $bioPage)
    {
        $bioPage->delete();

        return redirect()->route('admin.bio.index')
            ->with('success', 'Bio page deleted successfully');
    }

    public function togglePublish(BioPage $bioPage)
    {
        $bioPage->update([
            'is_published' => !$bioPage->is_published
        ]);

        return back()->with('success', 'Bio page status updated');
    }

    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:bio_pages,id'
        ]);

        BioPage::whereIn('id', $validated['ids'])->delete();

        return back()->with('success', count($validated['ids']) . ' bio pages deleted');
    }
}
