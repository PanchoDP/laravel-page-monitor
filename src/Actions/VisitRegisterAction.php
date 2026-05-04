<?php

declare(strict_types=1);

namespace Panchodp\LaravelPageMonitor\Actions;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Panchodp\LaravelPageMonitor\Models\PageVisit;

final readonly class VisitRegisterAction
{
    public function __construct(private DetectDeviceAction $detectDevice) {}

    public function handle(Request $request): void
    {
        $route = $request->route();
        $routeName = $route instanceof Route ? $route->getName() : null;
        $page = $routeName ?? $request->fullUrl();

        $userAgent = $request->userAgent() ?? '';

        PageVisit::create([
            'page' => $page,
            'user_id' => auth()->id(),
            'session_id' => session()->getId(),
            'ip_address' => $request->ip(),
            'device_type' => $this->detectDevice->handle($userAgent),
            'user_agent' => $userAgent,
            'visited_at' => now(),
        ]);
    }
}
