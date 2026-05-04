<?php

declare(strict_types=1);

namespace Panchodp\LaravelPageMonitor\Actions;

final class DetectDeviceAction
{
    public function handle(string $userAgent): string
    {
        $userAgent = strtolower($userAgent);

        if (str_contains($userAgent, 'tablet') || str_contains($userAgent, 'ipad')) {
            return 'tablet';
        }

        if (str_contains($userAgent, 'mobile') || str_contains($userAgent, 'android') || str_contains($userAgent, 'iphone')) {
            return 'mobile';
        }

        return 'desktop';
    }
}
