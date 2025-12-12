<?php

namespace App\Http\Controllers;

use App\Models\AbuseReport;
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

        return back()->with('status', 'Report updated.');
    }
}
