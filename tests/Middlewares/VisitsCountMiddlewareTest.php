<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Route;
use Panchodp\LaravelPageMonitor\Actions\DetectDeviceAction;
use Panchodp\LaravelPageMonitor\Actions\VisitRegisterAction;
use Panchodp\LaravelPageMonitor\Http\Middlewares\VisitsCountMiddleware;
use Panchodp\LaravelPageMonitor\Models\PageVisit;

it('passes the request through to the next handler', function (): void {
    $request = Request::create('http://localhost/home');
    $expected = new Response('ok', 200);

    $middleware = new VisitsCountMiddleware(new VisitRegisterAction(new DetectDeviceAction));
    $response = $middleware->handle($request, fn (): Response => $expected);

    expect($response)->toBe($expected);
});

it('registers a visit when handling a request', function (): void {
    $request = Request::create('http://localhost/home');

    $middleware = new VisitsCountMiddleware(new VisitRegisterAction(new DetectDeviceAction));
    $middleware->handle($request, fn ($r): Response => new Response);

    expect(PageVisit::count())->toBe(1)
        ->and(PageVisit::first()->page)->toBe('http://localhost/home');
});

it('skips visit registration for Livewire update requests', function (): void {
    $request = Request::create('http://localhost/livewire/update', 'POST');
    $request->headers->set('X-Livewire', 'true');

    $middleware = new VisitsCountMiddleware(new VisitRegisterAction(new DetectDeviceAction));
    $middleware->handle($request, fn ($r): Response => new Response);

    expect(PageVisit::count())->toBe(0);
});

it('skips visit registration for excluded routes', function (): void {
    config()->set('laravel_page_monitor.excluded_routes', ['api.*']);

    $request = Request::create('http://localhost/api/users');
    $route = new Route('GET', 'api/users', fn (): string => '');
    $route->name('api.users');
    $request->setRouteResolver(fn (): Route => $route);

    $middleware = new VisitsCountMiddleware(new VisitRegisterAction(new DetectDeviceAction));
    $middleware->handle($request, fn ($r): Response => new Response);

    expect(PageVisit::count())->toBe(0);
});
