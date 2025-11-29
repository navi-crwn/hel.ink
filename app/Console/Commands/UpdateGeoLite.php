<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class UpdateGeoLite extends Command
{
    protected $signature = 'geolite:update';

    protected $description = 'Download latest MaxMind GeoLite2 City database';

    public function handle(): int
    {
        $license = config('services.geolite.license_key');

        if (! $license) {
            $this->error('GEOLITE credentials missing.');
            return self::FAILURE;
        }

        $url = sprintf(
            'https://download.maxmind.com/app/geoip_download?edition_id=GeoLite2-City&license_key=%s&suffix=tar.gz',
            $license
        );

        $tempGz = storage_path('app/GeoLite2-City.tar.gz');
        $tempTar = storage_path('app/GeoLite2-City.tar');
        $target = config('services.geolite.city_db');
        $extractDir = storage_path('app/geoip_unpack');

        $this->info('Downloading GeoLite2...');
        $response = Http::timeout(120)->sink($tempGz)->get($url);

        if (! $response->ok()) {
            $this->error('Failed to download GeoLite2 database.');
            return self::FAILURE;
        }

        @unlink($tempTar);
        File::deleteDirectory($extractDir);

        $this->info('Extracting archive...');
        ini_set('phar.readonly', '0');
        $phar = new \PharData($tempGz);
        $phar->decompress();

        $tar = new \PharData($tempTar);
        $tar->extractTo($extractDir, null, true);

        $mmdb = collect(File::allFiles($extractDir))
            ->first(fn ($file) => $file->getExtension() === 'mmdb');

        if (! $mmdb) {
            $this->error('Could not locate mmdb file.');
            return self::FAILURE;
        }

        File::ensureDirectoryExists(dirname($target));
        File::copy($mmdb->getPathname(), $target);

        File::delete($tempGz, $tempTar);
        File::deleteDirectory($extractDir);

        $this->info('GeoLite2 database updated.');
        return self::SUCCESS;
    }
}
