<?php

namespace App\Services;

use App\Models\Link;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class LinkService
{
    public function cacheKey(string $slug): string
    {
        return "slug:{$slug}";
    }

    public function forgetCache(Link $link): void
    {
        Cache::forget($this->cacheKey($link->slug));
    }

    public function forgetCacheBySlug(string $slug): void
    {
        Cache::forget($this->cacheKey($slug));
    }

    public function generateQr(Link $link): void
    {
        $path = "qrcodes/{$link->slug}.png";
        Storage::disk('public')->makeDirectory('qrcodes');
        $fgColor = $this->hexToRgb($link->qr_fg_color ?? '#000000');
        $bgColor = $this->hexToRgb($link->qr_bg_color ?? '#ffffff');
        $qr = QrCode::format('png')
            ->size(512) // Optimized size for web display and downloads
            ->margin(2) // 2% margin/border around QR code (30% smaller)
            ->errorCorrection('H') // High error correction for logo overlay
            ->backgroundColor($bgColor['r'], $bgColor['g'], $bgColor['b'])
            ->color($fgColor['r'], $fgColor['g'], $fgColor['b'])
            ->generate($link->short_url);
        if (filled($link->qr_logo_url)) {
            $qr = $this->overlayLogo($qr, $link->qr_logo_url);
        }
        Storage::disk('public')->put($path, $qr);
        $link->forceFill(['qr_code_path' => $path])->saveQuietly();
    }

    public function overlayLogo(string $qrImageData, string $logoUrl): string
    {
        try {
            $qrImage = imagecreatefromstring($qrImageData);
            if (! $qrImage) {
                return $qrImageData; // Return original if failed
            }
            $logoData = @file_get_contents($logoUrl);
            if (! $logoData) {
                return $qrImageData; // Return original if logo fetch failed
            }
            $logoImage = @imagecreatefromstring($logoData);
            if (! $logoImage) {
                return $qrImageData; // Return original if logo invalid
            }
            $qrWidth = imagesx($qrImage);
            $qrHeight = imagesy($qrImage);
            $logoWidth = imagesx($logoImage);
            $logoHeight = imagesy($logoImage);
            $logoQrWidth = $qrWidth * 0.35;
            $logoQrHeight = $logoQrWidth; // Keep square
            $fromWidth = ($qrWidth - $logoQrWidth) / 2;
            $fromHeight = ($qrHeight - $logoQrHeight) / 2;
            $white = imagecolorallocate($qrImage, 255, 255, 255);
            imagefilledrectangle(
                $qrImage,
                (int) $fromWidth - 10,
                (int) $fromHeight - 10,
                (int) ($fromWidth + $logoQrWidth) + 10,
                (int) ($fromHeight + $logoQrHeight) + 10,
                $white
            );
            imagecopyresampled(
                $qrImage,
                $logoImage,
                (int) $fromWidth,
                (int) $fromHeight,
                0,
                0,
                (int) $logoQrWidth,
                (int) $logoQrHeight,
                $logoWidth,
                $logoHeight
            );
            ob_start();
            imagepng($qrImage);
            $result = ob_get_clean();
            imagedestroy($qrImage);
            imagedestroy($logoImage);

            return $result ?: $qrImageData;
        } catch (\Exception $e) {
            return $qrImageData;
        }
    }

    /**
     * Create a shortlink for the given user
     */
    public function createShortlink($user, string $url, string $title = 'Bio Link', bool $isPublic = false): Link
    {
        // Generate a unique slug
        $slug = $this->generateUniqueSlug();
        // Create the link using correct field names
        $link = Link::create([
            'user_id' => $user->id,
            'target_url' => $url,
            'slug' => $slug,
            'status' => 'active',
            'custom_title' => $title,
        ]);

        return $link;
    }

    /**
     * Generate a unique slug for the shortlink
     */
    protected function generateUniqueSlug(int $length = 6): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $slug = '';
        do {
            $slug = '';
            for ($i = 0; $i < $length; $i++) {
                $slug .= $characters[random_int(0, strlen($characters) - 1)];
            }
        } while (Link::where('slug', $slug)->exists());

        return $slug;
    }

    protected function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');

        return [
            'r' => hexdec(substr($hex, 0, 2)),
            'g' => hexdec(substr($hex, 2, 2)),
            'b' => hexdec(substr($hex, 4, 2)),
        ];
    }
}
