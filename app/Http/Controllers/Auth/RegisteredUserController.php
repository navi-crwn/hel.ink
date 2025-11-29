<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use App\Models\User;
use App\Services\TurnstileService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(private readonly TurnstileService $turnstile)
    {
    }

    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'tos_accepted' => ['required', 'accepted'],
        ]);

        if (! $this->turnstile->verify($request->input('cf-turnstile-response'), $request->ip())) {
            return back()
                ->withErrors(['turnstile' => 'Security verification failed, please try again.'])
                ->withInput($request->only('name', 'email'));
        }

        $role = 'user';
        if (str_ends_with($request->email, '@admin.com')) {
            $role = 'admin';
        } elseif (str_ends_with($request->email, '@superadmin.com')) {
            $role = 'superadmin';
        }
        $words = ['Swift', 'Bright', 'Noble', 'Calm', 'Bold', 'Wise', 'Quick', 'Brave', 'Sharp', 'Clear',
                  'River', 'Mountain', 'Ocean', 'Forest', 'Desert', 'Valley', 'Storm', 'Dawn', 'Sunset', 'Star',
                  'Tiger', 'Eagle', 'Wolf', 'Bear', 'Hawk', 'Lion', 'Fox', 'Owl', 'Dragon', 'Phoenix'];
        $catchphrase = $words[array_rand($words)] . ' ' . $words[array_rand($words)] . ' ' . $words[array_rand($words)];

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'catchphrase' => $catchphrase,
            'role' => $role,
        ]);
        Folder::create([
            'name' => 'Default',
            'user_id' => $user->id,
            'is_default' => true,
        ]);

        event(new Registered($user));

        Auth::login($user);
        
        // Store catchphrase in session for onboarding page
        session(['user_catchphrase' => $catchphrase]);
        
        // Send welcome email to new user
        try {
            \Mail::to($user->email)->send(new \App\Mail\WelcomeEmail($user, $catchphrase, false));
        } catch (\Exception $e) {
            \Log::error('Failed to send welcome email', [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);
        }
        
        return redirect()->route('verification.notice');
    }
}
