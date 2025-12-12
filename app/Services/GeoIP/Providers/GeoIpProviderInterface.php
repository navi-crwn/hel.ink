<?php

namespace App\Services\GeoIP\Providers;

interface GeoIpProviderInterface
{
    public function getName(): string;

    public function getPriority(): int;

    public function getDailyLimit(): int;

    public function getMonthlyLimit(): int;

    public function isAvailable(): bool;

    public function lookup(string $ip): ?array;

    public function details(string $ip): array;
}
