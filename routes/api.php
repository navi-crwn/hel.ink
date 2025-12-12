<?php

use App\Http\Controllers\Api\LinkController;
use App\Http\Controllers\Api\ShortenController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| These routes are loaded by the RouteServiceProvider within a group
| which is assigned the "api" middleware group.
|
*/
Route::middleware(['api.auth'])->group(function () {
    Route::post('/shorten', [ShortenController::class, 'store']);
});
// Shortlink creation and user library search - supports both Sanctum (API token) and web session auth
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/links', [LinkController::class, 'index']);
    Route::post('/shorten-web', [LinkController::class, 'shorten']);
    // Bio-specific shortlink creation (no limits applied)
    Route::post('/bio/shorten', [LinkController::class, 'shortenForBio']);
    // Utility to fetch title from URL
    Route::post('/utils/fetch-title', [LinkController::class, 'fetchTitle']);
});
