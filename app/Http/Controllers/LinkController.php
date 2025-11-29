<?php

namespace App\Http\Controllers;

use App\Http\Requests\ManageLinkRequest;
use App\Models\ActivityLog;
use App\Models\Folder;
use App\Models\Link;
use App\Models\LinkClick;
use App\Services\LinkService;
use App\Services\QuotaService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class LinkController extends Controller
{
    use HandlesSlugs;

    public function __construct(
        private readonly QuotaService $quotas,
        private readonly LinkService $links
    )
    {
        $this->middleware('quota')->only('store');
    }

    public function myLinks(Request $request)
    {
        $user = $request->user();
        
        $linksQuery = $user->links()->with(['folder', 'tags']);

        if ($search = $request->string('q')->trim()->toString()) {
            $linksQuery->where(function ($q) use ($search) {
                $q->where('slug', 'like', "%{$search}%")
                    ->orWhere('target_url', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%");
            });
        }

        if ($folderId = $request->integer('folder')) {
            $linksQuery->where('folder_id', $folderId);
        }

        if ($tagId = $request->integer('tag')) {
            $linksQuery->whereHas('tags', function ($query) use ($tagId) {
                $query->where('tags.id', $tagId);
            });
        }
        $sort = $request->string('sort')->toString() ?: 'created_desc';
        switch ($sort) {
            case 'created_asc':
                $linksQuery->oldest();
                break;
            case 'created_desc':
                $linksQuery->latest();
                break;
            case 'clicks_desc':
                $linksQuery->orderByDesc('clicks');
                break;
            case 'clicks_asc':
                $linksQuery->orderBy('clicks');
                break;
            case 'slug_asc':
                $linksQuery->orderBy('slug');
                break;
            case 'slug_desc':
                $linksQuery->orderByDesc('slug');
                break;
            default:
                $linksQuery->latest();
        }

        $links = $linksQuery->paginate(20)->withQueryString();
        
        return view('links-index', [
            'links' => $links,
            'folders' => $user->folders()->orderBy('name')->get(),
            'tags' => $user->tags()->orderBy('name')->get(),
            'filters' => [
                'q' => $request->string('q')->toString(),
                'folder' => $request->integer('folder'),
                'tag' => $request->integer('tag'),
                'sort' => $sort,
            ],
        ]);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        if ($user->isAdmin()) {
            $adminStats = [
                'total_users' => \App\Models\User::count(),
                'total_platform_links' => Link::count(),
                'total_platform_clicks' => LinkClick::count(),
                'today_platform_clicks' => LinkClick::whereDate('clicked_at', today())->count(),
                'suspended_users' => \App\Models\User::where('status', 'suspended')->count(),
                'inactive_links' => Link::where('status', Link::STATUS_INACTIVE)->count(),
                'ip_bans' => \App\Models\IpBan::count(),
                'queue_heartbeat' => \Illuminate\Support\Facades\Cache::get('queue:heartbeat'),
            ];

            $recentUsers = \App\Models\User::latest()->limit(5)->get();
            $recentPlatformLinks = Link::with('user')->latest()->limit(5)->get();
            $openReports = \App\Models\AbuseReport::orderByDesc('created_at')->limit(5)->get();
            $topPlatformCountries = LinkClick::select('country', DB::raw('COUNT(*) as total'))
                ->whereNotNull('country')
                ->groupBy('country')
                ->orderByDesc('total')
                ->limit(5)
                ->get();
            $folders = $user->folders;
            $tags = $user->tags;

            return view('admin-control-panel', compact(
                'adminStats',
                'recentUsers',
                'recentPlatformLinks',
                'openReports',
                'topPlatformCountries',
                'folders',
                'tags'
            ));
        }
        $linksQuery = $user->links()->with(['folder', 'tags']);

        if ($search = $request->string('q')->trim()->toString()) {
            $linksQuery->where(function ($q) use ($search) {
                $q->where('slug', 'like', "%{$search}%")
                    ->orWhere('target_url', 'like', "%{$search}%");
            });
        }

        if ($folderId = $request->integer('folder')) {
            $linksQuery->where('folder_id', $folderId);
        }

        if ($tagId = $request->integer('tag')) {
            $linksQuery->whereHas('tags', function ($query) use ($tagId) {
                $query->where('tags.id', $tagId);
            });
        }
        $sort = $request->string('sort')->toString() ?: 'created_desc';
        switch ($sort) {
            case 'created_asc':
                $linksQuery->oldest();
                break;
            case 'created_desc':
                $linksQuery->latest();
                break;
            case 'clicks_desc':
                $linksQuery->orderByDesc('clicks');
                break;
            case 'clicks_asc':
                $linksQuery->orderBy('clicks');
                break;
            case 'slug_asc':
                $linksQuery->orderBy('slug');
                break;
            case 'slug_desc':
                $linksQuery->orderByDesc('slug');
                break;
            default:
                $linksQuery->latest();
        }

        $links = $linksQuery->paginate(12)->withQueryString();
        $limits = config('limits.user');
        $todayCount = $user->links()->whereDate('created_at', today())->count();
        $latestLinks = $user->links()->latest()->limit(5)->get();
        $topLinks = $user->links()->orderByDesc('clicks')->limit(5)->get();
        $topCountry = LinkClick::query()
            ->whereHas('link', fn ($q) => $q->where('user_id', $user->id))
            ->select('country', DB::raw('COUNT(*) as total'))
            ->groupBy('country')
            ->orderByDesc('total')
            ->first();

        $stats = [
            'total_links' => $user->links()->count(),
            'total_clicks' => $user->links()->sum('clicks'),
            'active_links' => $user->links()->where('status', Link::STATUS_ACTIVE)->count(),
        ];

        $quota = [
            'daily' => [
                'used' => $todayCount,
                'limit' => $limits['max_links_per_day'],
            ],
            'active' => [
                'used' => $stats['active_links'],
                'limit' => $limits['max_active_links'],
            ],
        ];

        $data = [
            'links' => $links,
            'stats' => $stats,
            'recentLink' => session('shortlink') ?? $user->links()->latest()->first(),
            'folders' => $user->folders()->orderBy('name')->get(),
            'tags' => $user->tags()->orderBy('name')->get(),
            'filters' => [
                'q' => $request->string('q')->toString(),
                'folder' => $request->integer('folder'),
                'tag' => $request->integer('tag'),
                'sort' => $request->string('sort')->toString() ?: 'created_desc',
            ],
            'latestLinks' => $latestLinks,
            'topLinks' => $topLinks,
            'quota' => $quota,
            'topCountry' => $topCountry,
            'highlightLatest' => $latestLinks->first(),
            'highlightTop' => $topLinks->first(),
        ];
        if ($user->isSuperAdmin()) {
            $data['adminStats'] = [
                'total_users' => \App\Models\User::count(),
                'total_platform_links' => Link::count(),
                'total_platform_clicks' => LinkClick::count(),
                'today_platform_clicks' => LinkClick::whereDate('clicked_at', today())->count(),
                'suspended_users' => \App\Models\User::where('status', 'suspended')->count(),
                'inactive_links' => Link::where('status', Link::STATUS_INACTIVE)->count(),
                'ip_bans' => \App\Models\IpBan::count(),
                'queue_heartbeat' => \Illuminate\Support\Facades\Cache::get('queue:heartbeat'),
            ];

            $data['recentUsers'] = \App\Models\User::latest()->limit(5)->get();
            $data['recentPlatformLinks'] = Link::with('user')->latest()->limit(5)->get();
            $data['openReports'] = \App\Models\AbuseReport::orderByDesc('created_at')->limit(5)->get();
            $data['topPlatformCountries'] = LinkClick::select('country', DB::raw('COUNT(*) as total'))
                ->whereNotNull('country')
                ->groupBy('country')
                ->orderByDesc('total')
                ->limit(5)
                ->get();
        }

        return view('dashboard', $data);
    }

    public function create()
    {
        return view('link-create', [
            'folders' => auth()->user()->folders()->orderBy('name')->get(),
            'tags' => auth()->user()->tags()->orderBy('name')->get(),
        ]);
    }

    public function store(ManageLinkRequest $request)
    {
        if (! $this->quotas->checkUserLimits($request->user()->id)) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Daily link quota reached. Try again tomorrow.'], 429);
            }
            return back()->withErrors(['target_url' => 'Daily link quota reached. Try again tomorrow.']);
        }

        $data = $request->validated();
        if (empty($data['folder_id'])) {
            $defaultFolder = Folder::where('user_id', $request->user()->id)
                ->where('is_default', true)
                ->first();
            if ($defaultFolder) {
                $data['folder_id'] = $defaultFolder->id;
            }
        }
        
        $link = $request->user()->links()->create($this->mapPayload($data));
        $this->syncRelations($link, $request);
        if ($request->input('generate_qr', true)) {
            $this->links->generateQr($link);
        }
        
        $this->links->forgetCache($link);

        $link = $link->fresh();
        ActivityLog::log('created', 'Link', $link->id, "Created link: {$link->slug} → {$link->target_url}");
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'link' => [
                    'short_url' => $link->short_url,
                    'target_url' => $link->target_url,
                    'slug' => $link->slug,
                    'id' => $link->id,
                    'qr_code_url' => $link->qr_code_path ? Storage::url($link->qr_code_path) . '?t=' . time() : null,
                ],
                'message' => 'Link created successfully!',
            ]);
        }

        return redirect()
            ->route('dashboard')
            ->with('shortlink', $link)
            ->with('status', 'Link created successfully.');
    }

    public function edit(Link $link)
    {
        $this->authorizeLink($link);

        return view('link-edit', [
            'link' => $link->load('tags'),
            'folders' => auth()->user()->folders()->orderBy('name')->get(),
            'tags' => auth()->user()->tags()->orderBy('name')->get(),
        ]);
    }

    public function update(ManageLinkRequest $request, Link $link)
    {
        $this->authorizeLink($link);
        $data = $request->validated();
        $originalSlug = $link->slug;
        $originalRedirectType = $link->redirect_type;
        $originalTags = $link->tags->pluck('id')->toArray();
        
        $link->update($this->mapPayload($data, $link));
        $this->syncRelations($link, $request);
        $changes = [];
        if ($originalSlug !== $link->slug) {
            $changes[] = "slug: {$originalSlug} → {$link->slug}";
            $this->links->generateQr($link);
            $this->links->forgetCacheBySlug($originalSlug);
        }
        if ($originalRedirectType !== $link->redirect_type) {
            $changes[] = "redirect type: {$originalRedirectType} → {$link->redirect_type}";
        }
        $newTags = $link->fresh()->tags->pluck('id')->toArray();
        if ($originalTags != $newTags) {
            $changes[] = "tags updated";
        }
        
        $this->links->forgetCache($link);
        if (!empty($changes)) {
            ActivityLog::log('updated', 'Link', $link->id, "Updated link {$link->slug}: " . implode(', ', $changes));
        }

        return redirect()->route('dashboard')->with('status', 'Link diperbarui.');
    }

    public function destroy(Link $link)
    {
        $this->authorizeLink($link);
        $slug = $link->slug;
        $linkId = $link->id;
        
        if ($link->qr_code_path) {
            Storage::disk('public')->delete($link->qr_code_path);
        }
        $link->tags()->detach();
        $link->comments()->delete();
        $link->delete();
        $this->links->forgetCache($link);
        ActivityLog::log('deleted', 'Link', $linkId, "Deleted link: {$slug}");

        return redirect()->route('dashboard')->with('status', 'Link dihapus.');
    }

    public function show(Link $link)
    {
        $this->authorizeLink($link);

        $days = config('shortener.analytics_days', 30);
        $startDate = Carbon::now()->subDays($days);

        $dailyClicks = LinkClick::query()
            ->where('link_id', $link->id)
            ->where('clicked_at', '>=', $startDate)
            ->selectRaw('DATE(clicked_at) as day, COUNT(*) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day')
            ->toArray();
        $clicksQuery = LinkClick::where('link_id', $link->id)->where('clicked_at', '>=', $startDate);
        $countries = $clicksQuery->clone()
            ->whereNotNull('country')
            ->selectRaw('country, COUNT(*) as total')
            ->groupBy('country')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
            
        $cities = $clicksQuery->clone()
            ->whereNotNull('city')
            ->selectRaw('city, country, COUNT(*) as total')
            ->groupBy('city', 'country')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
        $isps = $clicksQuery->clone()
            ->whereNotNull('isp')
            ->selectRaw('isp, COUNT(*) as total')
            ->groupBy('isp')
            ->orderByDesc('total')
            ->limit(8)
            ->get();
        $proxyCount = $clicksQuery->clone()->where('is_proxy', true)->count();
        $totalClicks = $clicksQuery->clone()->count();
        $proxyPercentage = $totalClicks > 0 ? round(($proxyCount / $totalClicks) * 100, 1) : 0;
        $devices = $clicksQuery->clone()
            ->selectRaw('CASE WHEN user_agent LIKE "%Mobile%" THEN "Mobile" ELSE "Desktop" END as device, COUNT(*) as total')
            ->groupBy('device')
            ->pluck('total', 'device')
            ->toArray();
        $userAgentService = app(\App\Services\UserAgentService::class);
        $clicksForAgents = $clicksQuery->clone()->whereNotNull('user_agent')->take(500)->get();
        $browserStats = [];
        $osStats = [];
        
        foreach ($clicksForAgents as $click) {
            $parsed = $userAgentService->parse($click->user_agent);
            if (!empty($parsed['browser'])) {
                $browser = $parsed['browser'];
                $browserStats[$browser] = ($browserStats[$browser] ?? 0) + 1;
            }
            if (!empty($parsed['os'])) {
                $os = $parsed['os'];
                $osStats[$os] = ($osStats[$os] ?? 0) + 1;
            }
        }
        
        arsort($browserStats);
        arsort($osStats);
        $browserStats = array_slice($browserStats, 0, 6, true);
        $osStats = array_slice($osStats, 0, 6, true);
        $referers = $clicksQuery->clone()
            ->selectRaw('COALESCE(referer, "(direct)") as referer, COUNT(*) as total')
            ->groupBy('referer')
            ->orderByDesc('total')
            ->limit(8)
            ->pluck('total', 'referer');

        return view('link-show', [
            'link' => $link->load('tags', 'folder'),
            'dailyClicks' => $dailyClicks,
            'rangeInDays' => $days,
            'recentClicks' => LinkClick::where('link_id', $link->id)
                ->latest('clicked_at')
                ->limit(50)
                ->get(),
            'comments' => $link->comments()->latest()->with('user')->get(),
            'countries' => $countries,
            'cities' => $cities,
            'isps' => $isps,
            'proxyCount' => $proxyCount,
            'proxyPercentage' => $proxyPercentage,
            'devices' => $devices,
            'browserStats' => $browserStats,
            'osStats' => $osStats,
            'referers' => $referers,
            'totalClicks' => $totalClicks,
        ]);
    }

    protected function authorizeLink(Link $link): void
    {
        abort_unless(auth()->id() === $link->user_id, 403);
    }

    public function downloadQr(Link $link, string $format = 'png')
    {
        $this->authorizeLink($link);
        
        if (!in_array($format, ['png', 'svg', 'jpg'])) {
            abort(400, 'Invalid format');
        }
        
        $fgColor = $this->hexToRgb($link->qr_fg_color ?? '#000000');
        $bgColor = $this->hexToRgb($link->qr_bg_color ?? '#ffffff');
        
        $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format($format)
            ->size(512)
            ->margin(2)
            ->errorCorrection('H')
            ->backgroundColor($bgColor['r'], $bgColor['g'], $bgColor['b'])
            ->color($fgColor['r'], $fgColor['g'], $fgColor['b'])
            ->generate($link->short_url);
        if (filled($link->qr_logo_url) && in_array($format, ['png', 'jpg'])) {
            $qrCode = $this->links->overlayLogo($qrCode, $link->qr_logo_url);
        }
        
        $mimeTypes = [
            'png' => 'image/png',
            'svg' => 'image/svg+xml',
            'jpg' => 'image/jpeg',
        ];
        
        return response($qrCode)
            ->header('Content-Type', $mimeTypes[$format])
            ->header('Content-Disposition', "attachment; filename=\"{$link->slug}-qr.{$format}\"");
    }
    
    public function saveCustomQr(Request $request, Link $link)
    {
        $this->authorizeLink($link);
        
        $validated = $request->validate([
            'qr_image' => 'required|string',
            'settings' => 'nullable|array'
        ]);
        
        try {
            $imageData = $validated['qr_image'];
            if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
                $type = strtolower($type[1]); // jpg, png, gif
            } else {
                return response()->json(['error' => 'Invalid image format'], 400);
            }
            $imageData = base64_decode($imageData);
            
            if ($imageData === false) {
                return response()->json(['error' => 'Failed to decode image'], 400);
            }
            if (strlen($imageData) > 5 * 1024 * 1024) {
                return response()->json(['error' => 'Image too large (max 5MB)'], 400);
            }
            $path = "qrcodes/{$link->slug}-custom.png";
            Storage::disk('public')->put($path, $imageData);
            $link->update([
                'qr_code_path' => $path,
                'qr_settings' => $validated['settings'] ?? null
            ]);
            
            return response()->json([
                'success' => true,
                'path' => Storage::url($path),
                'message' => 'QR code saved successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to save QR code: ' . $e->getMessage()
            ], 500);
        }
    }
    
    protected function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        return [
            'r' => hexdec(substr($hex, 0, 2)),
            'g' => hexdec(substr($hex, 2, 2)),
            'b' => hexdec(substr($hex, 4, 2)),
        ];
    }

    protected function mapPayload(array $data, ?Link $link = null): array
    {
        $targetUrl = $data['target_url'];
        if (!preg_match('/^https?:\/\//i', $targetUrl)) {
            $targetUrl = 'https://' . $targetUrl;
        }
        
        $payload = [
            'target_url' => $targetUrl,
            'status' => $data['status'],
            'slug' => $this->prepareSlug($data['slug'] ?? null, $link),
            'folder_id' => blank($data['folder_id'] ?? null) ? null : $data['folder_id'],
            'redirect_type' => $data['redirect_type'] ?? '302',
            'custom_title' => $data['custom_title'] ?? null,
            'custom_description' => blank($data['custom_description'] ?? null) ? null : $data['custom_description'],
            'custom_image_url' => blank($data['custom_image_url'] ?? null) ? null : $data['custom_image_url'],
            'expires_at' => blank($data['expires_at'] ?? null) ? null : $data['expires_at'],
            'qr_fg_color' => $data['qr_fg_color'] ?? '#000000',
            'qr_bg_color' => $data['qr_bg_color'] ?? '#ffffff',
            'qr_logo_url' => blank($data['qr_logo_url'] ?? null) ? null : $data['qr_logo_url'],
        ];

        $password = $data['password'] ?? null;
        $removePassword = (bool) ($data['remove_password'] ?? false);

        if ($removePassword) {
            $payload['password_hash'] = null;
        } elseif (filled($password)) {
            $payload['password_hash'] = Hash::make($password);
        } elseif ($link) {
            $payload['password_hash'] = $link->password_hash;
        } else {
            $payload['password_hash'] = null;
        }

        return $payload;
    }

    protected function syncRelations(Link $link, ManageLinkRequest $request): void
    {
        $link->tags()->sync($request->input('tags', []));

        if ($request->filled('comment')) {
            $link->comments()->create([
                'body' => $request->input('comment'),
                'user_id' => $request->user()?->id,
            ]);
        }
    }

    public function fetchOgData(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
        ]);

        try {
            $client = new \GuzzleHttp\Client([
                'timeout' => 10,
                'verify' => false,
            ]);
            
            $response = $client->get($request->url, [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (compatible; HEL.ink/1.0; +https://hel.ink)',
                ],
            ]);
            
            $html = (string) $response->getBody();

            $ogData = [
                'title' => '',
                'description' => '',
                'image' => '',
                'favicon' => '',
                'url' => '',
                'site_name' => '',
            ];
            if (preg_match('/<meta[^>]+property=[\"\\\']og:title[\"\\\'][^>]+content=[\"\\\'](.*?)[\"\\\']/i', $html, $matches)) {
                $ogData['title'] = html_entity_decode($matches[1], ENT_QUOTES);
            } elseif (preg_match('/<title>(.*?)<\/title>/i', $html, $matches)) {
                $ogData['title'] = html_entity_decode($matches[1], ENT_QUOTES);
            }
            if (preg_match('/<meta[^>]+property=[\"\\\']og:description[\"\\\'][^>]+content=[\"\\\'](.*?)[\"\\\']/i', $html, $matches)) {
                $ogData['description'] = html_entity_decode($matches[1], ENT_QUOTES);
            } elseif (preg_match('/<meta[^>]+name=[\"\\\']description[\"\\\'][^>]+content=[\"\\\'](.*?)[\"\\\']/i', $html, $matches)) {
                $ogData['description'] = html_entity_decode($matches[1], ENT_QUOTES);
            }
            if (preg_match('/<meta[^>]+property=[\"\\\']og:image[\"\\\'][^>]+content=[\"\\\'](.*?)[\"\\\']/i', $html, $matches)) {
                $ogData['image'] = $matches[1];
            }
            if (preg_match('/<meta[^>]+property=[\"\\\']og:url[\"\\\'][^>]+content=[\"\\\'](.*?)[\"\\\']/i', $html, $matches)) {
                $ogData['url'] = $matches[1];
            }
            if (preg_match('/<meta[^>]+property=[\"\\\']og:site_name[\"\\\'][^>]+content=[\"\\\'](.*?)[\"\\\']/i', $html, $matches)) {
                $ogData['site_name'] = html_entity_decode($matches[1], ENT_QUOTES);
            }
            if (preg_match('/<link[^>]+rel=[\"\\\'](?:shortcut icon|icon)[\"\\\'][^>]+href=[\"\\\'](.*?)[\"\\\']/i', $html, $matches)) {
                $ogData['favicon'] = $matches[1];
            }

            return response()->json(['success' => true, 'data' => $ogData]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Could not fetch Open Graph data',
            ], 500);
        }
    }

}
