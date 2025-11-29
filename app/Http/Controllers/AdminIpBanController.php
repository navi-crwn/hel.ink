<?php

namespace App\Http\Controllers;

use App\Models\IpBan;
use Illuminate\Http\Request;

class AdminIpBanController extends Controller
{
    public function index()
    {
        $bans = IpBan::orderByDesc('created_at')->paginate(20);
        return view('admin-ip-bans', compact('bans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ip_address' => ['required', 'ip'],
            'reason' => ['nullable', 'string', 'max:255'],
            'expires_at' => ['nullable', 'date', 'after:now'],
        ]);

        IpBan::updateOrCreate(
            ['ip_address' => $data['ip_address']],
            ['reason' => $data['reason'] ?? null, 'expires_at' => $data['expires_at'] ?? null]
        );

        return back()->with('status', 'IP blocked successfully.');
    }

    public function destroy(IpBan $ipBan)
    {
        $ipBan->delete();
        return back()->with('status', 'IP removed from blocklist.');
    }
}
