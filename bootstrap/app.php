<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Global middleware to remove www prefix
        $middleware->prepend(\App\Http\Middleware\RemoveWwwPrefix::class);
        $middleware->appendToGroup('web', [
            \App\Http\Middleware\AutoLogout::class,
            \App\Http\Middleware\CheckSuspended::class,
            \App\Http\Middleware\EnsureGoogleUserHasPassword::class,
            \App\Http\Middleware\ContentSecurityPolicyMiddleware::class,
        ]);
        $middleware->alias([
            'admin' => \App\Http\AdminMiddleware::class,
            'ip.ban' => \App\Http\Middleware\CheckIpBan::class,
            'quota' => \App\Http\Middleware\CheckLinkQuota::class,
            '2fa' => \App\Http\Middleware\EnsureTwoFactorAuthenticated::class,
            'api.auth' => \App\Http\Middleware\ApiAuthenticate::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
