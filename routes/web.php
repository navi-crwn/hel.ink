<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminIpBanController;
use App\Http\Controllers\AdminIpWatchlistController;
use App\Http\Controllers\AdminLinkController;
use App\Http\Controllers\AdminQueueController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminAbuseController;
use App\Http\Controllers\AdminAnalyticsController;
use App\Http\Controllers\AdminDomainBlacklistController;
use App\Http\Controllers\GeoIpMonitorController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\Admin\SeoController;
use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\BrandAssetController;
use App\Http\Controllers\AbuseReportController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\LinkCommentController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\MarketingPageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicLinkController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TwoFactorController;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

RateLimiter::for('shorten', function (Request $request) {
    return [
        Limit::perMinute(10)->by($request->ip()),
    ];
});

Route::middleware('ip.ban')->group(function () {
    Route::get('/brand/logo.png', [BrandAssetController::class, 'logo'])->name('brand.logo');
    Route::get('/brand/logo-dark.png', [BrandAssetController::class, 'logoDark'])->name('brand.logo.dark');
    Route::get('/brand/favicon.png', [BrandAssetController::class, 'favicon'])->name('brand.favicon');

    Route::get('/', [PublicLinkController::class, 'index'])->name('home');
    Route::get('/onboarding', function () { return view('auth.onboarding'); })->name('onboarding');
    Route::post('/onboarding/skip', function () { return redirect()->route('dashboard'); })->name('onboarding.skip');
    Route::post('/onboarding/set-password', [ProfileController::class, 'createPasswordFromOnboarding'])->name('onboarding.set-password');
    Route::get('/goodbye', function () { return view('goodbye'); })->name('goodbye');
    Route::get('/about', [MarketingPageController::class, 'about'])->name('about');
    Route::get('/contact', [MarketingPageController::class, 'contact'])->name('contact');
    Route::get('/privacy', [MarketingPageController::class, 'privacy'])->name('privacy');
    Route::get('/terms', [MarketingPageController::class, 'terms'])->name('terms');
    Route::get('/tos', function () { return view('tos'); })->name('tos');
    Route::get('/plans', [MarketingPageController::class, 'plans'])->name('plans');
    Route::get('/help', [MarketingPageController::class, 'help'])->name('help');
    Route::get('/faq', [MarketingPageController::class, 'faq'])->name('faq');
    Route::get('/products', [MarketingPageController::class, 'products'])->name('products');
    Route::get('/feature-requests', [MarketingPageController::class, 'featureRequests'])->name('feature.requests');
    Route::post('/shorten', [PublicLinkController::class, 'store'])
        ->middleware(['throttle:shorten', 'quota'])
        ->name('shorten.store');

    Route::middleware(['auth'])->group(function () {
        Route::get('/two-factor-challenge', [TwoFactorController::class, 'show'])->name('two-factor.show');
        Route::post('/two-factor-challenge', [TwoFactorController::class, 'verify'])->name('two-factor.verify');
    });

    Route::middleware(['auth', '2fa'])->group(function () {
        Route::get('/dashboard', [LinkController::class, 'index'])->name('dashboard');
        Route::get('/links', [LinkController::class, 'myLinks'])->name('links.index');
        Route::get('/analytics', AnalyticsController::class)->name('analytics');
        Route::get('links/create', [LinkController::class, 'create'])->name('links.create');
        Route::post('links/fetch-og', [LinkController::class, 'fetchOgData'])->name('links.fetch-og');
        Route::get('links/{link}/qr/{format}', [LinkController::class, 'downloadQr'])
            ->where('format', 'png|svg|jpg')
            ->name('links.qr.download');
        Route::post('links/{link}/qr/save', [LinkController::class, 'saveCustomQr'])->name('links.qr.save');
        Route::resource('links', LinkController::class)->except(['index']);
        Route::post('links/{link}/comments', [LinkCommentController::class, 'store'])->name('links.comments.store');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
        Route::post('/settings/export', [SettingsController::class, 'exportData'])->name('settings.export');
        Route::post('/settings/export-analytics', [SettingsController::class, 'exportAnalytics'])->name('settings.export.analytics');
        Route::post('/settings/2fa/enable', [TwoFactorController::class, 'enable'])->name('settings.2fa.enable');
        Route::post('/settings/2fa/confirm', [TwoFactorController::class, 'confirm'])->name('settings.2fa.confirm');
        Route::delete('/settings/2fa/disable', [TwoFactorController::class, 'disable'])->name('settings.2fa.disable');
        Route::post('/settings/2fa/recovery-codes', [TwoFactorController::class, 'regenerateRecoveryCodes'])->name('settings.2fa.recovery-codes');
        Route::post('/api-tokens', [ApiTokenController::class, 'store'])->name('api-tokens.store');
        Route::delete('/api-tokens/{apiToken}', [ApiTokenController::class, 'destroy'])->name('api-tokens.destroy');
        Route::get('/api-docs', [ApiTokenController::class, 'docs'])->name('api-docs');
        Route::post('/api/sharex-config', [ApiTokenController::class, 'sharexConfig'])->name('api.sharex-config');
        Route::get('/folders', [FolderController::class, 'index'])->name('folders.index');
        Route::post('/folders', [FolderController::class, 'store'])->name('folders.store');
        Route::get('/folders/{folder}/manage', [FolderController::class, 'manage'])->name('folders.manage');
        Route::put('/folders/{folder}', [FolderController::class, 'update'])->name('folders.update');
        Route::delete('/folders/{folder}', [FolderController::class, 'destroy'])->name('folders.destroy');

        Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
        Route::post('/tags', [TagController::class, 'store'])->name('tags.store');
        Route::get('/tags/{tag}/manage', [TagController::class, 'manage'])->name('tags.manage');
        Route::put('/tags/{tag}', [TagController::class, 'update'])->name('tags.update');
        Route::delete('/tags/{tag}', [TagController::class, 'destroy'])->name('tags.destroy');
    });

    Route::middleware(['auth', 'admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/', function () {
                return redirect()->route('dashboard');
            })->name('dashboard');
            
            Route::get('/analytics/global', [AdminAnalyticsController::class, 'global'])->name('analytics.global');
            
            Route::post('/users', [AdminUserController::class, 'storeAdmin'])->name('users.store');
            Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
            Route::get('/users/{user}/inspect', [AdminUserController::class, 'inspect'])->name('users.inspect');
            Route::patch('/users/{user}/status', [AdminUserController::class, 'updateStatus'])->name('users.status');
            Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

            Route::get('/links', [AdminLinkController::class, 'index'])->name('links.index');
            Route::get('/links/my', [AdminLinkController::class, 'my'])->name('links.my');
            Route::patch('/links/{link}/status', [AdminLinkController::class, 'updateStatus'])->name('links.status');
            Route::delete('/links/{link}', [AdminLinkController::class, 'destroy'])->name('links.destroy');
            Route::post('/links/bulk', [AdminLinkController::class, 'bulk'])->name('links.bulk');

            Route::get('/ip-bans', [AdminIpBanController::class, 'index'])->name('ip-bans.index');
            Route::post('/ip-bans', [AdminIpBanController::class, 'store'])->name('ip-bans.store');
            Route::delete('/ip-bans/{ipBan}', [AdminIpBanController::class, 'destroy'])->name('ip-bans.destroy');
            
            Route::get('/ip-watchlist', [AdminIpWatchlistController::class, 'index'])->name('ip-watchlist.index');
            Route::post('/ip-watchlist', [AdminIpWatchlistController::class, 'store'])->name('ip-watchlist.store');
            Route::delete('/ip-watchlist/{watchlist}', [AdminIpWatchlistController::class, 'destroy'])->name('ip-watchlist.destroy');
            
            Route::get('/domain-blacklist', [AdminDomainBlacklistController::class, 'index'])->name('domain-blacklist.index');
            Route::post('/domain-blacklist', [AdminDomainBlacklistController::class, 'store'])->name('domain-blacklist.store');
            Route::delete('/domain-blacklist/{blacklist}', [AdminDomainBlacklistController::class, 'destroy'])->name('domain-blacklist.destroy');

            Route::get('/seo', [SeoController::class, 'edit'])->name('seo.edit');
            Route::post('/seo', [SeoController::class, 'update'])->name('seo.update');

            Route::get('/abuse', [AdminAbuseController::class, 'index'])->name('abuse.index');
            Route::patch('/abuse/{report}', [AdminAbuseController::class, 'update'])->name('abuse.update');
            
            Route::post('/queue/restart', [AdminQueueController::class, 'restart'])->name('queue.restart');
            Route::get('/queue/status', [AdminQueueController::class, 'status'])->name('queue.status');
            
            Route::get('/geoip-monitor', [GeoIpMonitorController::class, 'index'])->name('geoip.monitor');
            Route::post('/geoip-test', [GeoIpMonitorController::class, 'testProvider'])->name('geoip.test');
            
            Route::get('/proxy-monitor', [\App\Http\Controllers\ProxyMonitorController::class, 'index'])->name('proxy.monitor');
            Route::post('/proxy-test', [\App\Http\Controllers\ProxyMonitorController::class, 'test'])->name('proxy.test');
            Route::delete('/proxy-reset-stats', [\App\Http\Controllers\ProxyMonitorController::class, 'resetStats'])->name('proxy.reset-stats');
        });

    Route::get('/report-abuse', [AbuseReportController::class, 'create'])->name('report.create');
    Route::post('/report-abuse', [AbuseReportController::class, 'store'])->middleware('throttle:5,10')->name('report.store');
});

require __DIR__.'/auth.php';

Route::middleware('ip.ban')->match(['GET', 'POST'], '/{slug}', RedirectController::class)
    ->where('slug', '^(?!login$|logout$|register$|password.*|verify-email.*|email/.*|confirm-password.*|admin.*|dashboard$|settings$|links/|folders|tags|shorten$|about$|contact$|privacy$|terms$|plans$|help$|faq$|products$|feature-requests$|report-abuse$).*[A-Za-z0-9-]+$');

Route::get('/health/queue', function () {
    $heartbeat = cache('queue:heartbeat');
    return response()->json([
        'status' => $heartbeat ? 'ok' : 'stale',
        'last_heartbeat' => $heartbeat,
    ]);
});
Route::get('/test-modal', function() {
    return view('components.link-creation-modal', [
        'folders' => collect([]),
        'tags' => collect([]),
        'isAdmin' => false
    ]);
})->middleware('auth');
