<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HandlesSlugs;
use App\Models\Folder;
use App\Models\Link;
use App\Services\LinkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ShortenController extends Controller
{
    use HandlesSlugs;

    public function __construct(
        private LinkService $links
    ) {}

    /**
     * Create a shortened URL via API
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the request
        $minSlugLength = config('shortener.min_custom_slug_length', 3);
        $validator = Validator::make($request->all(), [
            'url' => 'required|url|max:2048',
            'alias' => "nullable|string|min:{$minSlugLength}|max:255|regex:/^[a-zA-Z0-9_-]+$/",
            'folder' => 'nullable|string',
            'expires_at' => 'nullable|date|after:now',
            'password' => 'nullable|string|min:4|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Validation Error',
                'messages' => $validator->errors()
            ], 422);
        }

        $user = $request->get('api_user');
        $targetUrl = $request->input('url');
        
        // Add https:// if missing
        if (!preg_match('/^https?:\/\//i', $targetUrl)) {
            $targetUrl = 'https://' . $targetUrl;
        }

        // Check user quota
        if (! app(\App\Services\QuotaService::class)->checkUserLimits($user->id)) {
            return response()->json([
                'success' => false,
                'error' => 'Quota Exceeded',
                'message' => 'Daily link quota reached. Please try again tomorrow.'
            ], 429);
        }

        try {
            // Prepare folder
            $folderId = null;
            if ($request->filled('folder')) {
                $folder = Folder::where('user_id', $user->id)
                    ->where('name', $request->input('folder'))
                    ->first();
                
                if (!$folder) {
                    // Create folder if not exists
                    $folder = Folder::create([
                        'user_id' => $user->id,
                        'name' => $request->input('folder'),
                        'is_default' => false,
                    ]);
                }
                $folderId = $folder->id;
            } else {
                // Use default folder
                $defaultFolder = Folder::where('user_id', $user->id)
                    ->where('is_default', true)
                    ->first();
                if ($defaultFolder) {
                    $folderId = $defaultFolder->id;
                }
            }

            // Prepare slug (alias)
            $slug = $this->prepareSlug($request->input('alias'));

            // Create the link
            $link = $user->links()->create([
                'target_url' => $targetUrl,
                'slug' => $slug,
                'status' => Link::STATUS_ACTIVE,
                'folder_id' => $folderId,
                'redirect_type' => '302',
                'expires_at' => $request->input('expires_at'),
                'password_hash' => $request->filled('password') 
                    ? \Illuminate\Support\Facades\Hash::make($request->input('password'))
                    : null,
            ]);

            // Generate QR code
            $this->links->generateQr($link);
            $this->links->forgetCache($link);

            // Log activity
            \App\Models\ActivityLog::log(
                'created', 
                'Link', 
                $link->id, 
                "Created link via API: {$link->slug} → {$link->target_url}"
            );

            // Return success response
            return response()->json([
                'success' => true,
                'data' => [
                    'url' => $link->short_url,
                    'slug' => $link->slug,
                    'target_url' => $link->target_url,
                    'created_at' => $link->created_at->toIso8601String(),
                    'expires_at' => $link->expires_at?->toIso8601String(),
                ],
                'message' => 'Link created successfully'
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Validation Error',
                'messages' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('API link creation failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Server Error',
                'message' => 'An error occurred while creating the link'
            ], 500);
        }
    }
}

