<?php

namespace App\Services\ProxyDetection;

interface ProxyDetectorInterface
{
    public function getName(): string;
    public function getPriority(): int;
    public function isAvailable(): bool;
    public function detect(string $ip): ?array;
    public function getDailyLimit(): int;
}
