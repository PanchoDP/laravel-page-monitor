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
            && $request->route()?->getName() !== 'page-monitor'
            && ! $request->attributes->get('_page_monitor_tracked')) {
            $this->visit_register->handle($request);
            $request->attributes->set('_page_monitor_tracked', true);
        }

        return $next($request);
    }
}
