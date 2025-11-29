<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Create password for Google OAuth users who don't have one yet
     */
    public function create(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        if (!$user->google_id || !empty($user->password)) {
            return back()->withErrors(['password' => 'Password creation is only for Google OAuth users without password.']);
        }

        $validated = $request->validateWithBag('updatePassword', [
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-created');
    }

    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        // Check if new password is same as current password
        if (Hash::check($validated['password'], $request->user()->password)) {
            return back()->withErrors([
                'password' => 'Your new password must be different from your current password. Please choose a new password.'
            ], 'updatePassword');
        }

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}
