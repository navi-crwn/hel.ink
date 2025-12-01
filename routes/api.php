<?php

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
