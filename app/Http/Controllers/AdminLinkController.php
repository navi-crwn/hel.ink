<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Link;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminLinkController extends Controller
{
    public function index(Request $request)
    {
        $query = Link::query()->with(['user', 'folder', 'tags'])->latest();
        $status = $request->string('status')->toString();
        $userId = $request->integer('user_id');
        $folderId = $request->integer('folder_id');
        $tagId = $request->integer('tag_id');

        if ($status !== '') {
            $query->where('status', $status);
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($folderId) {
            $query->where('folder_id', $folderId);
        }

        if ($tagId) {
            $query->whereHas('tags', fn ($q) => $q->where('tags.id', $tagId));
        }

        $search = $request->string('q')->trim()->toString();
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('slug', 'like', "%{$search}%")
                    ->orWhere('target_url', 'like', "%{$search}%");
            });
        }

        $links = $query->paginate(15)->withQueryString();

        return view('admin-links', [
            'links' => $links,
            'status' => $status,
            'search' => $search,
            'users' => User::orderBy('name')->get(['id', 'name']),
            'folders' => Folder::orderBy('name')->get(['id', 'name']),
            'tags' => Tag::orderBy('name')->get(['id', 'name']),
            'filters' => [
                'user_id' => $userId,
                'folder_id' => $folderId,
                'tag_id' => $tagId,
            ],
        ]);
    }
    
    public function my(Request $request)
    {
        $user = $request->user();
        $query = Link::query()
            ->where('user_id', $user->id)
            ->with(['folder', 'tags'])
            ->latest();
        
        $search = $request->string('q')->trim()->toString();
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('slug', 'like', "%{$search}%")
                    ->orWhere('target_url', 'like', "%{$search}%");
            });
        }
        
        $folderId = $request->integer('folder_id');
        if ($folderId) {
            $query->where('folder_id', $folderId);
        }
        
        $tagId = $request->integer('tag_id');
        if ($tagId) {
            $query->whereHas('tags', fn ($q) => $q->where('tags.id', $tagId));
        }
        
        $links = $query->paginate(15)->withQueryString();
        $folders = $user->folders;
        $tags = $user->tags;
        
        return view('admin-links-my', compact('links', 'search', 'folders', 'tags', 'folderId', 'tagId'));
    }

    public function updateStatus(Request $request, Link $link)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in([Link::STATUS_ACTIVE, Link::STATUS_INACTIVE])],
        ]);

        $link->update(['status' => $validated['status']]);

        return back()->with('status', "Link {$link->slug} status updated.");
    }

    public function destroy(Link $link)
    {
        $link->delete();

        return back()->with('status', "Link {$link->slug} deleted.");
    }

    public function bulk(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'action' => ['required', 'in:disable,delete'],
        ]);

        $ids = $request->input('ids');

        if ($request->action === 'delete') {
            Link::whereIn('id', $ids)->delete();
            $message = 'Link deleted successfully.';
        } else {
            Link::whereIn('id', $ids)->update(['status' => Link::STATUS_INACTIVE]);
            $message = 'Link dinonaktifkan.';
        }

        return back()->with('status', $message);
    }
}
