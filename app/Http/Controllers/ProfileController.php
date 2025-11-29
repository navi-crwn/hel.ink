<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Services\LinkService;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        private readonly LinkService $links
    )
    {
    }
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $originalName = $user->name;
        $originalEmail = $user->email;
        
        $user->fill($request->validated());
        if ($originalEmail !== $user->email) {
            $user->email_verified_at = null;
        }
        $user->save();
        $changes = [];
        if ($originalName !== $user->name) {
            $changes[] = "name: {$originalName} → {$user->name}";
        }
        if ($originalEmail !== $user->email) {
            $changes[] = "email: {$originalEmail} → {$user->email}";
        }
        
        if (!empty($changes)) {
            ActivityLog::log('updated', 'Profile', $user->id, "Updated profile: " . implode(', ', $changes));
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        // Check if user is superadmin
        if ($user->isSuperAdmin()) {
            return back()->withErrors(['delete' => 'The superadmin account cannot be deleted.']);
        }

        // Always require password for all users
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        try {
            \Log::info("Starting user deletion for ID: {$user->id}, Email: {$user->email}");
            
            // Collect statistics before deletion
            $stats = [
                'links_count' => $user->links()->count(),
                'folders_count' => $user->folders()->count(),
                'tags_count' => $user->tags()->count(),
                'total_clicks' => DB::table('link_clicks')->whereIn('link_id', $user->links()->pluck('id'))->count(),
            ];
            
            DB::transaction(function () use ($user) {
                foreach ($user->links as $link) {
                    $link->tags()->detach();
                }
                DB::table('comments')->where('user_id', $user->id)->delete();
                DB::table('activity_logs')->where('user_id', $user->id)->delete();
                DB::table('login_histories')->where('user_id', $user->id)->delete();
                DB::table('sessions')->where('user_id', $user->id)->delete();
                DB::table('link_clicks')->whereIn('link_id', $user->links()->pluck('id'))->delete();
                foreach ($user->links as $link) {
                    if ($link->qr_code_path) {
                        Storage::disk('public')->delete($link->qr_code_path);
                    }
                    $this->links->forgetCache($link);
                }
                DB::table('links')->where('user_id', $user->id)->delete();
                DB::table('folders')->where('user_id', $user->id)->delete();
                DB::table('tags')->where('user_id', $user->id)->delete();
                \Log::info("About to delete user record...");
                $deleted = DB::table('users')->where('id', $user->id)->delete();
                \Log::info("User deletion result: " . ($deleted ? 'SUCCESS' : 'FAILED') . " (rows affected: {$deleted})");
            });
            
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('goodbye')->with('deletion_stats', $stats);
        } catch (\Exception $e) {
            \Log::error("Failed to delete user account: " . $e->getMessage());
            \Log::error("Stack trace: " . $e->getTraceAsString());
            return back()->withErrors(['delete' => 'Failed to delete account. Please contact support.']);
        }
    }

    public function createPasswordFromOnboarding(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        // Only allow Google OAuth users with temporary password
        if (!$user->google_id) {
            return back()->withErrors(['password' => 'This action is not allowed.']);
        }
        
        $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
        ]);
        
        $user->update([
            'password' => bcrypt($request->password),
        ]);
        
        // Remove the flag from session
        session()->forget('google_user_needs_password');
        
        ActivityLog::log('created', 'Password', $user->id, "Created password from onboarding");
        
        return redirect()->route('dashboard')->with('status', 'password-created');
    }
}
