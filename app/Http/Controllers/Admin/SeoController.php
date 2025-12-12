<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SeoController extends Controller
{
    public function edit(): View
    {
        $seo = SeoSetting::first();

        return view('admin-seo', compact('seo'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'site_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string', 'max:500'],
            'og_image' => ['nullable', 'url', 'max:255'],
            'favicon' => ['nullable', 'url', 'max:255'],
            'logo' => ['nullable', 'url', 'max:255'],
        ]);
        SeoSetting::updateOrCreate(['id' => 1], $data);
        cache()->forget('seo.settings');

        return back()->with('status', 'SEO settings updated.');
    }
}
