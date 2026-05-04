<?php

declare(strict_types=1);

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Route;
use Panchodp\LaravelPageMonitor\Actions\DetectDeviceAction;
use Panchodp\LaravelPageMonitor\Actions\VisitRegisterAction;
use Panchodp\LaravelPageMonitor\Http\Middlewares\VisitsCountMiddleware;
use Panchodp\LaravelPageMonitor\Models\PageVisit;

function makeMiddleware(): VisitsCountMiddleware
{
    return new VisitsCountMiddleware(new VisitRegisterAction(new DetectDeviceAction));
}

it('middleware skips the page-monitor route', function (): void {
    $request = Request::create('/page-monitor');
    $request->setRouteResolver(fn () => tap(new Route('GET', '/page-monitor', []), function ($route): void {
        $route->name('page-monitor');
    }));

    makeMiddleware()->handle($request, fn ($r): Response => new Response);

    expect(PageVisit::count())->toBe(0);
});

it('middleware prevents double counting on the same request', function (): void {
    $request = Request::create('http://localhost/home');

    makeMiddleware()->handle($request, fn ($r): Response => new Response);
    makeMiddleware()->handle($request, fn ($r): Response => new Response);

    expect(PageVisit::count())->toBe(1);
});

it('track_all adds middleware to the web group', function (): void {
    $this->app['router']->pushMiddlewareToGroup('web', VisitsCountMiddleware::class);

    expect($this->app['router']->getMiddlewareGroups()['web'])
        ->toContain(VisitsCountMiddleware::class);
});

it('track_all registers visits when middleware runs on a request', function (): void {
    $this->app['router']->pushMiddlewareToGroup('web', VisitsCountMiddleware::class);

    $request = Request::create('http://localhost/any-page');
    makeMiddleware()->handle($request, fn ($r): Response => new Response);

    expect(PageVisit::count())->toBe(1);
});

it('track_all does not register visits for the page-monitor dashboard', function (): void {
    $this->app['router']->pushMiddlewareToGroup('web', VisitsCountMiddleware::class);

    $user = User::forceCreate(['name' => 'Test', 'email' => 'test@example.com', 'password' => 'password']);
    $this->actingAs($user)->get('/page-monitor');

    expect(PageVisit::count())->toBe(0);
});

it('track_all does not double count when middleware is also applied explicitly', function (): void {
    $this->app['router']->pushMiddlewareToGroup('web', VisitsCountMiddleware::class);
    $this->app['router']->middleware(['web', 'visits-count'])->get('/test-explicit', fn (): string => 'ok');

    $this->get('/test-explicit');

    expect(PageVisit::count())->toBe(1);
});
