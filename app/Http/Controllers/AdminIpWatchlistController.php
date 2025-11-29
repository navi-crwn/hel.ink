<?php

namespace App\Http\Controllers;

use App\Models\IpWatchlist;
use Illuminate\Http\Request;

class AdminIpWatchlistController extends Controller
{
    public function index()
    {
        $watchlist = IpWatchlist::with('user')
            ->orderBy('last_attempt_at', 'desc')
            ->paginate(20);
        
        return view('admin-ip-watchlist', compact('watchlist'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|string',
            'reason' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        $ipAddresses = array_filter(array_map('trim', explode("\n", $request->ip_address)));
        $addedCount = 0;
        $errors = [];
        
        foreach ($ipAddresses as $ip) {
            if (!filter_var($ip, FILTER_VALIDATE_IP)) {
                $errors[] = "Invalid IP: {$ip}";
                continue;
            }
            if (IpWatchlist::where('ip_address', $ip)->exists()) {
                $errors[] = "Already exists: {$ip}";
                continue;
            }
            
            IpWatchlist::addOrUpdate(
                $ip,
                null,
                $request->reason ?? 'Manually added by admin'
            );
            
            if ($request->notes) {
                $watchlist = IpWatchlist::where('ip_address', $ip)->first();
                $watchlist->update(['notes' => $request->notes]);
            }
            
            $addedCount++;
        }
        
        if ($addedCount > 0 && count($errors) === 0) {
            return back()->with('status', "{$addedCount} IP address(es) added to watchlist");
        } elseif ($addedCount > 0 && count($errors) > 0) {
            return back()->with('status', "{$addedCount} IP(s) added. Errors: " . implode(', ', $errors));
        } else {
            return back()->withErrors(['ip_address' => implode(', ', $errors)]);
        }
    }
    
    public function destroy(IpWatchlist $watchlist)
    {
        $watchlist->delete();
        
        return back()->with('status', 'IP removed from watchlist');
    }
}
