<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'catchphrase' => ['required', 'string'],
        ]);
        $user = \App\Models\User::where('email', $request->email)->first();
        if (! $user || $user->catchphrase !== $request->catchphrase) {
            return back()->withErrors(['catchphrase' => 'The provided email or catchphrase is incorrect.'])->withInput($request->only('email'));
        }
        session(['password_reset_email' => $request->email]);

        return redirect()->route('password.reset.form');
    }

    public function storeEmailOnly(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);
        // Send password reset link via Laravel's built-in password reset
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['status' => __($status)])
                    : back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm(): View
    {
        if (! session('password_reset_email')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password-form');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $email = session('password_reset_email');
        if (! $email) {
            return redirect()->route('password.request')->withErrors(['email' => 'Session expired. Please try again.']);
        }
        $user = \App\Models\User::where('email', $email)->first();
        if (! $user) {
            return redirect()->route('password.request')->withErrors(['email' => 'User not found.']);
        }
        $user->update([
            'password' => bcrypt($request->password),
        ]);
        session()->forget('password_reset_email');

        return redirect()->route('login')->with('status', 'Your password has been reset successfully. You can now login with your new password.');
    }
}
