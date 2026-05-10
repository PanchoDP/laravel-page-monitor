<?php

declare(strict_types=1);

namespace Panchodp\LaravelPageMonitor\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Panchodp\LaravelPageMonitor\Actions\VisitRegisterAction;
use Symfony\Component\HttpFoundation\Response;

final readonly class VisitsCountMiddleware
{
    public function __construct(private VisitRegisterAction $visit_register) {}

    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (config('laravel_page_monitor.enabled', true)
            && ! $request->hasHeader('X-Livewire')
            && ! $this->isExcludedRoute($request)
            && ! $request->attributes->get('_page_monitor_tracked')
            && (config('laravel_page_monitor.track_guests', true) || auth()->check())) {
            $this->visit_register->handle($request);
            $request->attributes->set('_page_monitor_tracked', true);
        }

        return $next($request);
    }

    private function isExcludedRoute(Request $request): bool
    {
        $routeName = $request->route()?->getName();

        if ($routeName === 'page-monitor') {
            return true;
        }

        /** @var list<string> $excluded */
        $excluded = config('laravel_page_monitor.excluded_routes', []);

        foreach ($excluded as $pattern) {
            if ($routeName !== null && str($routeName)->is($pattern)) {
                return true;
            }
        }

        return false;
    }
}
