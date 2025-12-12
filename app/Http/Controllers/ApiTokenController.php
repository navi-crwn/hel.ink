<?php

namespace App\Http\Controllers;

use App\Models\ApiToken;
use Illuminate\Http\Request;

class ApiTokenController extends Controller
{
    /**
     * Store a newly created API token
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        // Generate token
        $result = ApiToken::generate(
            userId: $request->user()->id,
            name: $request->input('name'),
            rateLimit: 100
        );
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'token' => $result['plain_token'],
                'name' => $result['model']->name,
                'message' => 'API token created successfully. Save this token - you won\'t be able to see it again!',
            ]);
        }

        return back()->with('status', 'API token created!')->with('api_token', $result['plain_token']);
    }

    /**
     * Remove the specified API token
     */
    public function destroy(ApiToken $apiToken)
    {
        // Ensure user owns this token
        if ($apiToken->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
        $apiToken->delete();
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'API token revoked successfully',
            ]);
        }

        return back()->with('status', 'API token revoked successfully');
    }

    /**
     * Show API documentation
     */
    public function docs()
    {
        return view('api-docs');
    }

    /**
     * Download ShareX configuration file
     */
    public function sharexConfig()
    {
        $config = [
            'Version' => '15.0.0',
            'Name' => 'hel.ink URL Shortener',
            'DestinationType' => 'URLShortener',
            'RequestMethod' => 'POST',
            'RequestURL' => url('/api/shorten'),
            'Headers' => [
                'Authorization' => 'Bearer YOUR_API_TOKEN_HERE',
                'Content-Type' => 'application/json',
            ],
            'Body' => 'JSON',
            'Data' => json_encode([
                'url' => '{input}',
            ]),
            'URL' => '{json:data.url}',
        ];
        $filename = 'helink-sharex-config.sxcu';
        $content = json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        return response($content)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }
}
