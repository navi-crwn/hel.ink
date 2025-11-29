<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TurnstileService
{
    public function verify(?string $token, ?string $ip = null): bool
    {
        if (app()->runningUnitTests() || app()->environment('testing') || ! config('services.turnstile.secret')) {
            return true;
        }

        if (blank($token)) {
            return false;
        }

        $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'secret' => config('services.turnstile.secret'),
            'response' => $token,
            'remoteip' => $ip,
        ]);

        \Log::info('Turnstile API response', [
            'status' => $response->status(),
            'body' => $response->json(),
        ]);

        if (! $response->ok()) {
            return false;
        }

        return (bool) data_get($response->json(), 'success', false);
    }
}
