<?php

namespace App\Http\Controllers;

use App\Models\BioPage;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $staticPages = [
            ['loc' => url('/'), 'priority' => '1.0', 'changefreq' => 'daily'],
            ['loc' => route('about'), 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => route('products'), 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => route('faq'), 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['loc' => route('help'), 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['loc' => route('contact'), 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => route('privacy'), 'priority' => '0.5', 'changefreq' => 'yearly'],
            ['loc' => route('terms'), 'priority' => '0.5', 'changefreq' => 'yearly'],
            ['loc' => route('plans'), 'priority' => '0.6', 'changefreq' => 'monthly'],
        ];

        $bioPages = BioPage::query()
            ->where('is_published', true)
            ->where('allow_indexing', true)
            ->where('is_adult_content', false)
            ->latest('updated_at')
            ->limit(1000)
            ->get(['slug', 'updated_at']);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($staticPages as $page) {
            $xml .= '<url>';
            $xml .= '<loc>'.htmlspecialchars($page['loc']).'</loc>';
            $xml .= '<changefreq>'.$page['changefreq'].'</changefreq>';
            $xml .= '<priority>'.$page['priority'].'</priority>';
            $xml .= '</url>';
        }

        foreach ($bioPages as $bio) {
            $xml .= '<url>';
            $xml .= '<loc>'.htmlspecialchars(url('/b/'.$bio->slug)).'</loc>';
            $xml .= '<lastmod>'.$bio->updated_at->toW3cString().'</lastmod>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.6</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
}
