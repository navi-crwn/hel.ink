<?php

namespace App\Http\Controllers;

use App\Models\BioClick;
use App\Models\BioPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BioPageController extends Controller
{
    public function index()
    {
        $bioPages = auth()->user()->bioPages()->withCount('links')->latest()->get();

        return view('bio.index', compact('bioPages'));
    }

    public function create()
    {
        return view('bio.create');
    }

    public function checkSlug(Request $request)
    {
        $slug = $request->input('slug');
        if (! $slug || strlen($slug) < 3) {
            return response()->json(['available' => false]);
        }
        if (! preg_match('/^[a-zA-Z0-9]+$/', $slug)) {
            return response()->json(['available' => false]);
        }
        $slug = strtolower($slug);
        $exists = BioPage::where('slug', $slug)->exists();
        $reserved = in_array($slug, config('shortener.reserved_slugs', []));

        return response()->json([
            'available' => ! $exists && ! $reserved,
        ]);
    }

    public function generateQR(Request $request)
    {
        $data = $request->input('data');
        $size = $request->input('size', 300);
        $color = $request->input('color', '000000');
        $bgcolor = $request->input('bgcolor', 'ffffff');
        $logoData = $request->input('logo'); // Base64 logo image
        if (! $data) {
            return response('Missing data parameter', 400);
        }
        try {
            // Generate QR code with high error correction for logo overlay
            $qrString = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                ->size($size)
                ->color(hexdec(substr($color, 0, 2)), hexdec(substr($color, 2, 2)), hexdec(substr($color, 4, 2)))
                ->backgroundColor(hexdec(substr($bgcolor, 0, 2)), hexdec(substr($bgcolor, 2, 2)), hexdec(substr($bgcolor, 4, 2)))
                ->errorCorrection('H') // High error correction for logo overlay
                ->margin(1)
                ->generate($data);
            // If logo is provided, overlay it on the QR code
            if ($logoData && str_starts_with($logoData, 'data:image')) {
                // Create image from QR code
                $qrImage = @imagecreatefromstring($qrString);
                if (! $qrImage) {
                    return response($qrString)->header('Content-Type', 'image/png');
                }
                // Extract base64 data and decode
                $logoData = preg_replace('/^data:image\/\w+;base64,/', '', $logoData);
                $logoDecoded = base64_decode($logoData);
                if (! $logoDecoded) {
                    imagedestroy($qrImage);

                    return response($qrString)->header('Content-Type', 'image/png');
                }
                $logo = @imagecreatefromstring($logoDecoded);
                if (! $logo) {
                    imagedestroy($qrImage);

                    return response($qrString)->header('Content-Type', 'image/png');
                }
                // Get dimensions
                $qrWidth = imagesx($qrImage);
                $qrHeight = imagesy($qrImage);
                $logoWidth = imagesx($logo);
                $logoHeight = imagesy($logo);
                // Logo size: 20% of QR code size
                $logoSize = (int) ($qrWidth * 0.2);
                $logoX = (int) (($qrWidth - $logoSize) / 2);
                $logoY = (int) (($qrHeight - $logoSize) / 2);
                // Create white background for logo (slightly larger for padding)
                $bgSize = (int) ($logoSize + 20);
                $bgX = (int) (($qrWidth - $bgSize) / 2);
                $bgY = (int) (($qrHeight - $bgSize) / 2);
                $white = imagecolorallocate($qrImage, 255, 255, 255);
                imagefilledrectangle($qrImage, $bgX, $bgY, $bgX + $bgSize, $bgY + $bgSize, $white);
                // Overlay logo on QR code with resampling for quality
                imagecopyresampled(
                    $qrImage,
                    $logo,
                    $logoX,
                    $logoY,
                    0,
                    0,
                    $logoSize,
                    $logoSize,
                    $logoWidth,
                    $logoHeight
                );
                // Output final image
                ob_start();
                imagepng($qrImage, null, 9); // Max compression
                $finalQr = ob_get_clean();
                // Clean up
                imagedestroy($qrImage);
                imagedestroy($logo);

                return response($finalQr)->header('Content-Type', 'image/png');
            }

            return response($qrString)->header('Content-Type', 'image/png');
        } catch (\Exception $e) {
            return response('Error generating QR code', 500);
        }
    }

    private function handleQrImage($settings, $oldSettings = null)
    {
        if (isset($settings['image']) && str_starts_with($settings['image'], 'data:image')) {
            $base64Image = $settings['image'];
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
                $data = substr($base64Image, strpos($base64Image, ',') + 1);
                $extension = strtolower($type[1]);
                if (in_array($extension, ['jpg', 'jpeg', 'gif', 'png'])) {
                    $data = base64_decode($data);
                    $filename = 'qr-logos/'.uniqid().'.'.$extension;
                    Storage::disk('public')->put($filename, $data);
                    $settings['image'] = '/storage/'.$filename;
                }
            }
        }
        $oldImage = $oldSettings['image'] ?? $oldSettings['logo_url'] ?? null;
        $newImage = $settings['image'] ?? $settings['logo_url'] ?? null;
        if ($oldImage && $oldImage !== $newImage && str_starts_with($oldImage, '/storage/')) {
            $path = str_replace('/storage/', '', $oldImage);
            Storage::disk('public')->delete($path);
        }

        return $settings;
    }

    public function store(Request $request)
    {
        if (auth()->user()->bioPages()->count() >= 3) {
            return back()->withErrors(['slug' => 'You have reached the maximum limit of 3 bio pages.']);
        }
        $request->merge(['slug' => strtolower($request->slug)]);
        $validated = $request->validate([
            'slug' => [
                'required',
                'string',
                'min:3',
                'max:20',
                'regex:/^[a-zA-Z0-9]+$/',
                'unique:bio_pages,slug',
                Rule::notIn(config('shortener.reserved_slugs', [])),
            ],
            'title' => 'required|string|max:45',
            'bio' => 'nullable|string|max:155',
            'qr_settings' => 'nullable|json',
        ]);
        $qrSettings = $request->filled('qr_settings') ? json_decode($request->qr_settings, true) : null;
        if ($qrSettings) {
            $qrSettings = $this->handleQrImage($qrSettings);
        }
        $bioPage = auth()->user()->bioPages()->create([
            'slug' => $validated['slug'],
            'title' => $validated['title'],
            'bio' => $validated['bio'],
            'theme' => 'default',
            'qr_settings' => $qrSettings,
        ]);

        return redirect()->route('bio.edit', $bioPage)->with('success', 'Bio page created successfully!');
    }

    public function edit(BioPage $bioPage)
    {
        $this->authorize('update', $bioPage);
        $bioPage->load(['links' => function ($query) {
            $query->orderBy('order');
        }]);
        $brands = config('brands.platforms', []);

        // Use the new editor view
        return view('bio.editor.index', compact('bioPage', 'brands'));
    }

    public function update(Request $request, BioPage $bioPage)
    {
        $this->authorize('update', $bioPage);
        if ($request->has('slug')) {
            $request->merge(['slug' => strtolower($request->slug)]);
        }
        $validated = $request->validate([
            'slug' => [
                'sometimes',
                'required',
                'string',
                'min:3',
                'max:20',
                'regex:/^[a-zA-Z0-9]+$/',
                Rule::unique('bio_pages')->ignore($bioPage->id),
            ],
            'title' => 'sometimes|required|string|max:45',
            'bio' => 'nullable|string|max:155',
            'theme' => 'nullable|string|max:50',
            'layout' => 'nullable|string|max:50',
            'background_type' => 'nullable|in:color,gradient,image,solid',
            'background_value' => 'nullable|string',
            'background_color' => 'nullable|string|max:100',
            'background_gradient' => 'nullable|string|max:255',
            'background_image' => 'nullable|string|max:500',
            'text_color' => 'nullable|string|max:50',
            'title_color' => 'nullable|string|max:50',
            'bio_color' => 'nullable|string|max:50',
            'link_bg_color' => 'nullable|string|max:100',
            'link_text_color' => 'nullable|string|max:50',
            'button_bg_color' => 'nullable|string|max:100',
            'button_text_color' => 'nullable|string|max:50',
            'button_shape' => 'nullable|string|max:50',
            'button_shadow' => 'nullable|string|max:50',
            'header_bg_color' => 'nullable|string|max:50',
            'font_family' => 'nullable|string|max:50',
            'block_shape' => 'nullable|string|max:50',
            'block_shadow' => 'nullable|string|max:50',
            'social_icon_color' => 'nullable|string|max:20',
            'social_placement' => 'nullable|in:top,bottom',
            'social_links' => 'nullable|array',
            'social_links.*' => 'nullable|array',
            'social_links.*.platform' => 'nullable|string',
            'social_links.*.value' => 'nullable|string',
            'social_links.*.enabled' => 'nullable|boolean',
            'socials' => 'nullable|array',
            'socials_position' => 'nullable|in:top,bottom',
            'social_icons_position' => 'nullable|in:below_bio,bottom_page',
            'avatar_shape' => 'nullable|in:circle,rounded,square',
            'badge' => 'nullable|in:verified,star,crown,fire',
            'badge_color' => 'nullable|string|max:20',
            'seo_title' => 'nullable|string|max:47',
            'seo_description' => 'nullable|string|max:160',
            'seo_noindex' => 'nullable|boolean',
            'custom_css' => 'nullable|string',
            'is_published' => 'nullable|boolean',
            'is_adult_content' => 'nullable|boolean',
            'is_public' => 'nullable|boolean',
            'show_in_directory' => 'nullable|boolean',
            'hide_branding' => 'nullable|boolean',
            'google_analytics_id' => 'nullable|string|max:50',
            'facebook_pixel_id' => 'nullable|string|max:50',
            'avatar' => 'nullable|image|max:2048',
            'avatar_url' => 'nullable|string',
            'password_enabled' => 'nullable|boolean',
            'password' => 'nullable|string|max:50',
            'allow_indexing' => 'nullable|boolean',
            'qr_settings' => 'nullable|array',
            'hover_effect' => 'nullable|string|max:50',
            'background_animation' => 'nullable|string|max:50',
        ]);
        if ($request->hasFile('avatar')) {
            if ($bioPage->avatar_url) {
                Storage::disk('public')->delete($bioPage->avatar_url);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar_url'] = $path;
        }
        if (isset($validated['socials'])) {
            $validated['socials'] = json_encode($validated['socials']);
        }
        if (isset($validated['qr_settings'])) {
            $validated['qr_settings'] = $this->handleQrImage($validated['qr_settings'], $bioPage->qr_settings);
        }
        // Ensure theme is not null (database doesn't allow null)
        if (array_key_exists('theme', $validated) && $validated['theme'] === null) {
            $validated['theme'] = 'default';
        }
        $bioPage->update($validated);
        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Bio page updated successfully!',
                'bioPage' => $bioPage->fresh(),
            ]);
        }

        return back()->with('success', 'Bio page updated successfully!');
    }

    public function destroy(BioPage $bioPage)
    {
        $this->authorize('delete', $bioPage);
        if ($bioPage->avatar_url) {
            Storage::disk('public')->delete($bioPage->avatar_url);
        }
        $bioPage->delete();

        return redirect()->route('bio.index')->with('success', 'Bio page deleted successfully!');
    }

    public function analytics(BioPage $bioPage)
    {
        $this->authorize('view', $bioPage);
        $days = request('days', 30);
        $clicksData = BioClick::query()
            ->whereIn('bio_link_id', $bioPage->links->pluck('id'))
            ->where('clicked_at', '>=', now()->subDays($days))
            ->selectRaw('DATE(clicked_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        $viewsChartLabels = [];
        $viewsChartData = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $viewsChartLabels[] = now()->subDays($i)->format('M d');
            $clickCount = $clicksData->firstWhere('date', $date);
            $viewsChartData[] = $clickCount ? $clickCount->count : 0;
        }
        $topLinks = $bioPage->links()
            ->where('is_active', true)
            ->orderByDesc('click_count')
            ->limit(10)
            ->get();
        $countriesData = BioClick::query()
            ->whereIn('bio_link_id', $bioPage->links->pluck('id'))
            ->where('clicked_at', '>=', now()->subDays($days))
            ->whereNotNull('country')
            ->selectRaw('country, COUNT(*) as clicks')
            ->groupBy('country')
            ->orderByDesc('clicks')
            ->limit(10)
            ->get();
        $totalCountryClicks = $countriesData->sum('clicks');
        $topCountries = $countriesData->map(function ($item) use ($totalCountryClicks) {
            $item->percentage = $totalCountryClicks > 0 ? round(($item->clicks / $totalCountryClicks) * 100, 1) : 0;
            $item->country_name = $item->country;
            $item->flag = $this->getCountryFlag($item->country);

            return $item;
        });
        $devicesData = BioClick::query()
            ->whereIn('bio_link_id', $bioPage->links->pluck('id'))
            ->where('clicked_at', '>=', now()->subDays($days))
            ->whereNotNull('device')
            ->selectRaw('device, COUNT(*) as count')
            ->groupBy('device')
            ->orderByDesc('count')
            ->get();
        $deviceChartLabels = $devicesData->pluck('device')->map(function ($device) {
            return ucfirst($device);
        })->toArray();
        $deviceChartData = $devicesData->pluck('count')->toArray();
        $recentClicks = BioClick::query()
            ->with('bioLink')
            ->whereIn('bio_link_id', $bioPage->links->pluck('id'))
            ->orderByDesc('clicked_at')
            ->limit(20)
            ->get();
        $totalClicks = $bioPage->getTotalClicks();
        $ctr = $bioPage->view_count > 0 ? round(($totalClicks / $bioPage->view_count) * 100, 2) : 0;
        $stats = [
            'totalViews' => $bioPage->view_count,
            'totalClicks' => $totalClicks,
            'ctr' => $ctr,
            'activeLinks' => $bioPage->links()->where('is_active', true)->count(),
            'viewsChart' => [
                'labels' => $viewsChartLabels,
                'data' => $viewsChartData,
            ],
            'deviceChart' => [
                'labels' => $deviceChartLabels,
                'data' => $deviceChartData,
            ],
            'topLinks' => $topLinks,
            'topCountries' => $topCountries,
            'recentClicks' => $recentClicks,
        ];

        return view('bio.analytics', compact('bioPage', 'stats'));
    }

    private function getCountryFlag($countryCode)
    {
        $flags = [
            'US' => '🇺🇸', 'ID' => '🇮🇩', 'GB' => '🇬🇧', 'CA' => '🇨🇦', 'AU' => '🇦🇺',
            'DE' => '🇩🇪', 'FR' => '🇫🇷', 'JP' => '🇯🇵', 'CN' => '🇨🇳', 'IN' => '🇮🇳',
            'SG' => '🇸🇬', 'MY' => '🇲🇾', 'TH' => '🇹🇭', 'PH' => '🇵🇭', 'VN' => '🇻🇳',
        ];

        return $flags[$countryCode] ?? '🌍';
    }

    public function duplicate(BioPage $bioPage)
    {
        $this->authorize('view', $bioPage);
        $newSlug = $bioPage->slug.'-copy';
        $counter = 1;
        while (BioPage::where('slug', $newSlug)->exists()) {
            $newSlug = $bioPage->slug.'-copy-'.$counter;
            $counter++;
        }
        $newBioPage = $bioPage->replicate(['view_count']);
        $newBioPage->slug = $newSlug;
        $newBioPage->title = $bioPage->title.' (Copy)';
        $newBioPage->save();
        foreach ($bioPage->links as $link) {
            $newLink = $link->replicate(['click_count']);
            $newLink->bio_page_id = $newBioPage->id;
            $newLink->save();
        }

        return redirect()->route('bio.edit', $newBioPage)->with('success', 'Bio page duplicated successfully!');
    }

    /**
     * Upload image for bio page blocks
     */
    public function uploadImage(Request $request, BioPage $bioPage)
    {
        $this->authorize('update', $bioPage);
        $request->validate([
            'image' => 'required|image|max:5120', // 5MB max
        ]);
        try {
            $path = $request->file('image')->store('bio-images', 'public');

            return response()->json([
                'success' => true,
                'path' => $path,
                'url' => '/storage/'.$path,
                'image_url' => '/storage/'.$path,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Upload avatar for bio page
     */
    public function uploadAvatar(Request $request, BioPage $bioPage)
    {
        $this->authorize('update', $bioPage);
        $request->validate([
            'avatar' => 'required|image|max:2048', // 2MB max
        ]);
        try {
            // Delete old avatar if exists
            if ($bioPage->avatar_url) {
                Storage::disk('public')->delete($bioPage->avatar_url);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $bioPage->update(['avatar_url' => $path]);

            return response()->json([
                'success' => true,
                'avatar_url' => $path, // Return just the path
                'full_url' => '/storage/'.$path,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload avatar: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Upload thumbnail for bio link block
     */
    public function uploadThumbnail(Request $request, BioPage $bioPage)
    {
        $this->authorize('update', $bioPage);
        $request->validate([
            'thumbnail' => 'required|image|max:1024', // 1MB max
        ]);
        try {
            $path = $request->file('thumbnail')->store('bio-thumbnails', 'public');

            return response()->json([
                'success' => true,
                'thumbnail_url' => '/storage/'.$path,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload thumbnail: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Upload custom icon for bio link block
     */
    public function uploadIcon(Request $request, BioPage $bioPage)
    {
        $this->authorize('update', $bioPage);
        $request->validate([
            'icon' => 'required|image|max:1024', // 1MB max
        ]);
        try {
            $path = $request->file('icon')->store('bio-icons', 'public');

            return response()->json([
                'success' => true,
                'icon_url' => '/storage/'.$path,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload icon: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Upload QR code logo
     */
    public function uploadQrLogo(Request $request, BioPage $bioPage)
    {
        $this->authorize('update', $bioPage);
        $request->validate([
            'logo' => 'required|image|max:1024', // 1MB max
        ]);
        try {
            // Delete old logo if exists
            if ($bioPage->qr_settings && isset($bioPage->qr_settings['logo_url'])) {
                $oldPath = str_replace('/storage/', '', $bioPage->qr_settings['logo_url']);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('logo')->store('qr-logos', 'public');
            // Update qr_settings with new logo URL
            $qrSettings = $bioPage->qr_settings ?? [];
            $qrSettings['logo_url'] = '/storage/'.$path;
            $bioPage->update(['qr_settings' => $qrSettings]);

            return response()->json([
                'success' => true,
                'logo_url' => '/storage/'.$path,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload QR logo: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Preview bio page (for iframe in editor)
     */
    public function preview(BioPage $bioPage)
    {
        $this->authorize('view', $bioPage);
        $bioPage->load(['links' => function ($query) {
            $query->orderBy('order');
        }]);

        return view('bio.editor.preview', compact('bioPage'));
    }
}
