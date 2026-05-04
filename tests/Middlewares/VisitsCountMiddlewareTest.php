<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
