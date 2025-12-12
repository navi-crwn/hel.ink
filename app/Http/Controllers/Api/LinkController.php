<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Link;
use App\Services\LinkService;

class LinkController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([], 200);
        }

        $query = trim((string) $request->get('query', ''));
        $links = Link::query()
            ->where('user_id', $user->id)
            ->when($query !== '', function ($q) use ($query) {
                $q->where(function ($sub) use ($query) {
                    $sub->where('custom_title', 'like', "%{$query}%")
                        ->orWhere('target_url', 'like', "%{$query}%")
                        ->orWhere('slug', 'like', "%{$query}%");
                });
            })
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();
        
        // Map to expected format
        $result = $links->map(function ($link) {
            return [
                'id' => $link->id,
                'title' => $link->custom_title ?: 'Untitled',
                'url' => $link->target_url,
                'short_url' => $link->short_url,
            ];
        });

        return response()->json($result);
    }

    public function shorten(Request $request, LinkService $linkService)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'url' => ['required', 'string'],
            'title' => ['nullable', 'string'],
            'is_public' => ['nullable', 'boolean'],
        ]);

        // Normalize URL: add https if missing, keep http if present
        $url = trim($data['url']);
        if (!preg_match('/^https?:\/\//i', $url)) {
            $url = 'https://' . ltrim($url, '/');
        }

        // Use LinkService to create a shortlink for the user
        $link = $linkService->createShortlink(
            $user,
            $url,
            $data['title'] ?? 'Bio Link',
            (bool) ($data['is_public'] ?? false)
        );

        return response()->json([
            'id' => $link->id,
            'title' => $link->title,
            'url' => $link->url,
            'short_url' => $link->short_url,
        ], 201);
    }

    /**
     * Create shortlink for bio pages (no limits applied)
     */
    public function shortenForBio(Request $request, LinkService $linkService)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'url' => ['required', 'string'],
            'title' => ['nullable', 'string'],
        ]);

        // Normalize URL: add https if missing, keep http if present
        $url = trim($data['url']);
        if (!preg_match('/^https?:\/\//i', $url)) {
            $url = 'https://' . ltrim($url, '/');
        }

        try {
            // Check if user already has a shortlink for this URL (prevent duplicates)
            $existing = \App\Models\Link::where('user_id', $user->id)
                ->where('target_url', $url)
                ->first();
            
            if ($existing) {
                return response()->json([
                    'id' => $existing->id,
                    'title' => $existing->custom_title,
                    'url' => $existing->target_url,
                    'short_url' => $existing->short_url,
                    'reused' => true,
                ], 200);
            }
            
            // Create shortlink directly without any limit checks
            $link = $linkService->createShortlink(
                $user,
                $url,
                $data['title'] ?? 'Bio Link',
                false // is_public
            );

            return response()->json([
                'id' => $link->id,
                'title' => $link->custom_title,
                'url' => $link->target_url,
                'short_url' => $link->short_url,
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Bio shorten error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to create shortlink: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function fetchTitle(Request $request)
    {
        $request->validate(['url' => 'required|url']);
        $url = $request->input('url');
        
        try {
            $client = new \GuzzleHttp\Client(['timeout' => 5, 'verify' => false]);
            $response = $client->get($url, [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
                ]
            ]);
            $html = (string) $response->getBody();
            
            if (preg_match('/<title>(.*?)<\/title>/i', $html, $matches)) {
                return response()->json(['title' => html_entity_decode(trim($matches[1]))]);
            }
        } catch (\Exception $e) {
            // Ignore errors
        }
        
        return response()->json(['title' => null]);
    }
}
