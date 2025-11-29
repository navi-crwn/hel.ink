<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $loginHistory = DB::table('users')
            ->where('id', $user->id)
            ->select('last_login_at', 'last_login_ip', 'last_login_country', 'last_login_city', 'last_login_provider', 'last_login_device', 'last_login_browser')
            ->first();
        $loginHistories = DB::table('login_histories')
            ->where('user_id', $user->id)
            ->orderBy('logged_in_at', 'desc')
            ->limit(5)
            ->get();
        $activityQuery = DB::table('activity_logs')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc');
        if ($request->filled('activity_from')) {
            $activityQuery->whereDate('created_at', '>=', $request->activity_from);
        }
        if ($request->filled('activity_to')) {
            $activityQuery->whereDate('created_at', '<=', $request->activity_to);
        }

        $activityLogs = $activityQuery->limit(50)->get();
        $folders = $user->folders()->orderBy('name')->get();
        $tags = $user->tags()->orderBy('name')->get();

        return view('settings', [
            'loginHistory' => $loginHistory,
            'loginHistories' => $loginHistories,
            'activityLogs' => $activityLogs,
            'folders' => $folders,
            'tags' => $tags,
        ]);
    }

    public function exportData(Request $request)
    {
        $user = $request->user();
        
        $query = $user->links()->with(['folder', 'tags'])->withCount('clicks');
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('folder_id')) {
            if ($request->folder_id === 'default') {
                $query->whereNull('folder_id');
            } else {
                $query->where('folder_id', $request->folder_id);
            }
        }
        if ($request->filled('tag_id')) {
            $query->whereHas('tags', function($q) use ($request) {
                $q->where('tags.id', $request->tag_id);
            });
        }
        if ($request->filled('min_clicks')) {
            $query->having('clicks_count', '>=', $request->min_clicks);
        }
        
        $sortBy = $request->input('sort_by', 'created_at');
        if ($sortBy === 'most_clicked') {
            $query->orderByDesc('clicks_count');
        } elseif ($sortBy === 'least_clicked') {
            $query->orderBy('clicks_count');
        } elseif ($sortBy === 'newest') {
            $query->orderByDesc('created_at');
        } elseif ($sortBy === 'oldest') {
            $query->orderBy('created_at');
        } else {
            $query->orderByDesc('created_at');
        }
        
        $links = $query->get();
        
        $filename = 'helink-export-' . now()->format('Y-m-d-His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function() use ($links) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Short Link',
                'Destination URL',
                'Redirect Type',
                'Folder',
                'Tags',
                'Password Protected',
                'Status',
                'Total Clicks',
                'Unique Visitors',
                'Created At',
                'Expires At',
                'Last Clicked'
            ]);
            
            foreach ($links as $link) {
                $uniqueVisitors = $link->clicks()
                    ->distinct('ip_address')
                    ->count('ip_address');
                
                $lastClicked = $link->clicks()
                    ->latest('clicked_at')
                    ->first();
                
                fputcsv($file, [
                    $link->short_url,
                    $link->target_url,
                    $link->redirect_type ?? '302',
                    $link->folder->name ?? 'Default',
                    $link->tags->pluck('name')->join(', '),
                    $link->password ? 'Yes' : 'No',
                    ucfirst($link->status),
                    $link->clicks_count,
                    $uniqueVisitors,
                    $link->created_at->format('Y-m-d H:i:s'),
                    $link->expires_at ? $link->expires_at->format('Y-m-d H:i:s') : 'Never',
                    $lastClicked ? $lastClicked->clicked_at->format('Y-m-d H:i:s') : 'Never'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function exportAnalytics(Request $request)
    {
        return $this->exportData($request);
    }
}

