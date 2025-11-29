<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\User;
use App\Services\LinkService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;

class AdminUserController extends Controller
{
    public function __construct(private readonly LinkService $links)
    {
    }

    public function index(Request $request)
    {
        $query = User::query()->latest();
        $search = $request->string('q')->trim()->toString();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin-users', [
            'users' => $users,
            'search' => $search,
            'viewerIsSuperAdmin' => $request->user()->isSuperAdmin(),
        ]);
    }

    public function inspect(Request $request, User $user)
    {
        $user->load(['links' => function($query) {
            $query->latest()->with(['folder', 'tags']);
        }, 'folders', 'tags']);

        return view('admin-user-inspect', [
            'user' => $user,
            'totalClicks' => $user->links->sum('clicks'),
            'viewerIsSuperAdmin' => $request->user()->isSuperAdmin(),
        ]);
    }

    public function storeAdmin(Request $request)
    {
        abort_unless($request->user()->isSuperAdmin(), 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => User::ROLE_USER,
            'status' => User::STATUS_ACTIVE,
            'email_verified_at' => now(),
        ]);
        Folder::create([
            'name' => 'Default',
            'user_id' => $user->id,
            'is_default' => true,
        ]);

        return back()->with('status', "{$user->name} was added as a user.");
    }

    public function updateStatus(Request $request, User $user)
    {
        if ($user->isSuperAdmin()) {
            return back()->withErrors(['status' => 'The superadmin account cannot be suspended.']);
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in([User::STATUS_ACTIVE, User::STATUS_SUSPENDED])],
        ]);

        if ($user->id === $request->user()->id && $validated['status'] !== User::STATUS_ACTIVE) {
            return back()->withErrors(['status' => 'Anda tidak bisa mensuspend akun sendiri.']);
        }

        $user->update(['status' => $validated['status']]);

        return back()->with('status', "Status {$user->name} diperbarui.");
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return back()->withErrors(['delete' => 'You cannot remove the account currently signed in.']);
        }

        if ($user->isSuperAdmin()) {
            return back()->withErrors(['delete' => 'The superadmin account cannot be deleted.']);
        }

        $user->links()->each(function ($link) {
            if ($link->qr_code_path) {
                Storage::disk('public')->delete($link->qr_code_path);
            }
            $link->tags()->detach();
            $link->comments()->delete();
            $link->delete();
            $this->links->forgetCache($link);
        });
        $user->folders()->delete();
        $user->tags()->delete();
        $user->delete();

        return back()->with('status', "{$user->email} has been removed.");
    }
}
