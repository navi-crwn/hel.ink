<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BrandAssetController extends Controller
{
    public function logo(): BinaryFileResponse
    {
        return $this->serve('Logo.png');
    }

    public function logoDark(): BinaryFileResponse
    {
        return $this->serve('Logo-dark.png');
    }

    public function favicon(): BinaryFileResponse
    {
        return $this->serve('Favicon.png');
    }

    protected function serve(string $filename): BinaryFileResponse
    {
        $path = resource_path("img/{$filename}");
        abort_unless(is_file($path), 404);

        return response()->file($path, [
            'Cache-Control' => 'public, max-age='.(60 * 60 * 24 * 7).', immutable',
            'Content-Type' => 'image/png',
        ]);
    }
}
