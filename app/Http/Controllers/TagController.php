<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $tags = $request->user()
            ->tags()
            ->withCount('links')
            ->with(['links' => function ($query) {
                $query->orderByDesc('links.created_at');
            }])
            ->orderBy('name')
            ->paginate(20);
        return view('tags', compact('tags'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:60'],
        ]);

        $tag = $request->user()->tags()->create($data);
        ActivityLog::log('created', 'Tag', $tag->id, "Created tag: {$tag->name}");

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'tag' => [
                    'id' => $tag->id,
                    'name' => $tag->name,
                ],
                'message' => 'Tag ditambahkan.',
            ]);
        }

        return back()->with('status', 'Tag added successfully.');
    }

    public function update(Request $request, Tag $tag): RedirectResponse
    {
        abort_unless($tag->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:60'],
        ]);

        $originalName = $tag->name;
        $tag->update($data);
        if ($originalName !== $tag->name) {
            ActivityLog::log('updated', 'Tag', $tag->id, "Updated tag: {$originalName} → {$tag->name}");
        }

        return back()->with('status', 'Tag updated successfully.');
    }

    public function destroy(Request $request, Tag $tag): RedirectResponse
    {
        abort_unless($tag->user_id === $request->user()->id, 403);

        $tagName = $tag->name;
        $tagId = $tag->id;
        $tag->links()->detach();
        $tag->delete();
        ActivityLog::log('deleted', 'Tag', $tagId, "Deleted tag: {$tagName}");

        return back()->with('status', 'Tag deleted successfully.');
    }

    public function manage(Request $request, Tag $tag): View
    {
        abort_unless($tag->user_id === $request->user()->id, 403);

        $search = $request->string('q')->trim()->toString();

        $linksQuery = $tag->links()->with(['folder', 'tags'])->orderByDesc('links.created_at');

        if ($search) {
            $linksQuery->where(function ($query) use ($search) {
                $query->where('links.slug', 'like', "%{$search}%")
                    ->orWhere('links.target_url', 'like', "%{$search}%");
            });
        }

        $links = $linksQuery->paginate(15)->withQueryString();

        return view('edit-tag', [
            'tag' => $tag,
            'links' => $links,
            'search' => $search,
        ]);
    }
}
