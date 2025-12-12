<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Folder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FolderController extends Controller
{
    public function index(Request $request)
    {
        $folders = $request->user()
            ->folders()
            ->withCount('links')
            ->with(['links' => function ($query) {
                $query->orderByDesc('created_at');
            }])
            ->orderBy('name')
            ->paginate(12);

        return view('folders', compact('folders'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
        ]);
        $folder = $request->user()->folders()->create($data);
        ActivityLog::log('created', 'Folder', $folder->id, "Created folder: {$folder->name}");
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'folder' => [
                    'id' => $folder->id,
                    'name' => $folder->name,
                ],
                'message' => 'Folder added successfully.',
            ]);
        }

        return back()->with('status', 'Folder added successfully.');
    }

    public function update(Request $request, Folder $folder): RedirectResponse
    {
        abort_unless($folder->user_id === $request->user()->id, 403);
        if ($folder->is_default) {
            return back()->withErrors(['folder' => 'Default folder cannot be edited.']);
        }
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
        ]);
        $originalName = $folder->name;
        $folder->update($data);
        if ($originalName !== $folder->name) {
            ActivityLog::log('updated', 'Folder', $folder->id, "Updated folder: {$originalName} → {$folder->name}");
        }

        return back()->with('status', 'Folder updated successfully.');
    }

    public function destroy(Request $request, Folder $folder): RedirectResponse
    {
        abort_unless($folder->user_id === $request->user()->id, 403);
        if ($folder->links()->exists()) {
            return back()->withErrors(['folder' => 'Folder still contains links. Please move them first.']);
        }
        $folderName = $folder->name;
        $folderId = $folder->id;
        $folder->delete();
        ActivityLog::log('deleted', 'Folder', $folderId, "Deleted folder: {$folderName}");

        return back()->with('status', 'Folder deleted successfully.');
    }

    public function manage(Request $request, Folder $folder): View
    {
        abort_unless($folder->user_id === $request->user()->id, 403);
        $search = $request->string('q')->trim()->toString();
        $linksQuery = $folder->links()->with(['tags', 'folder'])->orderByDesc('created_at');
        if ($search) {
            $linksQuery->where(function ($query) use ($search) {
                $query->where('slug', 'like', "%{$search}%")
                    ->orWhere('target_url', 'like', "%{$search}%");
            });
        }
        $links = $linksQuery->paginate(15)->withQueryString();

        return view('edit-folder', [
            'folder' => $folder,
            'links' => $links,
            'search' => $search,
        ]);
    }
}
