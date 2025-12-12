<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    public function store(Request $request): RedirectResponse
    {
        // Rate limiting: 5 attempts per hour per email
        $key = 'password-reset:'.strtolower($request->email);
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => sprintf('Too many password reset attempts. Please try again in %d minutes.', ceil($seconds / 60)),
            ]);
        }
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        $user = User::where('email', $request->email)->first();
        if ($user && filled($user->pin)) {
            $request->validate([
                'pin' => ['required', 'string', 'size:6'],
            ]);
            if ($user->pin !== $request->pin) {
                RateLimiter::hit($key, 3600);

                return back()->withErrors(['pin' => 'Invalid PIN provided.']);
            }
        }
        // Check if new password is same as old password
        if ($user && Hash::check($request->password, $user->password)) {
            RateLimiter::hit($key, 3600);
            throw ValidationException::withMessages([
                'password' => 'Please choose a new password. Do not use your old password for security reasons.',
            ]);
        }
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request, $key) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();
                event(new PasswordReset($user));
                // Clear rate limiter on successful reset
                RateLimiter::clear($key);
            }
        );
        // Hit rate limiter on failure
        if ($status !== Password::PASSWORD_RESET) {
            RateLimiter::hit($key, 3600);
        }

        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
