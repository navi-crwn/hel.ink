<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleAuthController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        // Check if called from register page
        if (request()->get('from_register') == '1') {
            session(['google_oauth_from' => 'register']);
            \Log::info('Google OAuth: Setting session to REGISTER');
        } else {
            session(['google_oauth_from' => 'login']);
            \Log::info('Google OAuth: Setting session to LOGIN');
        }
        
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $fromPage = session('google_oauth_from', 'login');
            
            \Log::info('Google OAuth Callback', [
                'email' => $googleUser->email,
                'from_page' => $fromPage,
                'session_id' => session()->getId()
            ]);
            
            session()->forget('google_oauth_from');
            
            // Check if user exists by email or google_id
            $user = User::where('email', $googleUser->email)
                       ->orWhere('google_id', $googleUser->id)
                       ->first();

            // If from login page but user doesn't exist, redirect to register
            if ($fromPage === 'login' && !$user) {
                \Log::info('User not found, redirecting to register', ['email' => $googleUser->email]);
                return redirect()->route('login')
                    ->with('error', 'Account not found. Please register first to continue.')
                    ->with('google_email', $googleUser->email);
            }

            // If from register page but user exists, redirect to login
            if ($fromPage === 'register' && $user) {
                \Log::info('User already exists, redirecting to login', ['email' => $googleUser->email]);
                return redirect()->route('login')->with('error', 'Account already exists. Please login instead.');
            }

            if ($user) {
                // Update google_id if not set
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                    ]);
                }

                // Check if user is suspended
                if ($user->isSuspended()) {
                    return redirect()->route('suspended')->with('error', 'Your account has been suspended.');
                }

                // Login existing user
                Auth::login($user, true);

                return redirect()->intended('dashboard')->with('success', 'Welcome back, ' . $user->name . '!');
            } else {
                // Generate catchphrase for new user
                $words = ['Swift', 'Bright', 'Noble', 'Calm', 'Bold', 'Wise', 'Quick', 'Brave', 'Sharp', 'Clear',
                          'River', 'Mountain', 'Ocean', 'Forest', 'Desert', 'Valley', 'Storm', 'Dawn', 'Sunset', 'Star',
                          'Tiger', 'Eagle', 'Wolf', 'Bear', 'Hawk', 'Lion', 'Fox', 'Owl', 'Dragon', 'Phoenix'];
                $catchphrase = $words[array_rand($words)] . ' ' . $words[array_rand($words)] . ' ' . $words[array_rand($words)];

                // Create new user WITHOUT password (will be set in onboarding)
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'password' => Hash::make('TEMPORARY_GOOGLE_OAUTH_NO_PASSWORD_' . Str::random(32)), // Temporary password
                    'email_verified_at' => now(), // Google already verified email
                    'catchphrase' => $catchphrase,
                ]);

                // Send notification to admin about new user
                try {
                    $this->notificationService->notifyNewUser([
                        'name' => $user->name,
                        'email' => $user->email,
                        'registration_method' => 'Google OAuth',
                        'registered_at' => $user->created_at->format('Y-m-d H:i:s'),
                    ]);
                } catch (Exception $e) {
                    \Log::error('Failed to send new user notification', [
                        'error' => $e->getMessage(),
                        'user_id' => $user->id
                    ]);
                }

                // Send welcome email to new user
                try {
                    \Mail::to($user->email)->send(new \App\Mail\WelcomeEmail($user, $catchphrase, true));
                } catch (Exception $e) {
                    \Log::error('Failed to send welcome email', [
                        'error' => $e->getMessage(),
                        'user_id' => $user->id
                    ]);
                }

                // Create default folder
                \App\Models\Folder::create([
                    'name' => 'Default',
                    'user_id' => $user->id,
                    'is_default' => true,
                ]);

                // Login new user
                Auth::login($user, true);

                // Store catchphrase in session for onboarding
                session(['user_catchphrase' => $catchphrase]);
                session(['google_user_needs_password' => true]); // Mark that user needs to set password

                return redirect()->route('onboarding')->with('success', 'Welcome to ' . config('app.name') . ', ' . $user->name . '!');
            }

        } catch (Exception $e) {
            \Log::error('Google OAuth Error: ' . $e->getMessage());
            
            return redirect()->route('login')
                ->with('error', 'Unable to login with Google. You may not have registered yet. Please register first.');
        }
    }
}
