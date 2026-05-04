<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Panchodp\LaravelPageMonitor\Actions\DetectDeviceAction;
use Panchodp\LaravelPageMonitor\Actions\VisitRegisterAction;
use Panchodp\LaravelPageMonitor\Models\PageVisit;

function makeAction(): VisitRegisterAction
{
    return new VisitRegisterAction(new DetectDeviceAction);
}

it('registers visit using route name', function (): void {
    $request = Request::create('/about');
    $request->setRouteResolver(fn () => tap(new Route('GET', '/about', []), function ($route): void {
        $route->name('about');
    }));

    makeAction()->handle($request);

    expect(PageVisit::count())->toBe(1)
        ->and(PageVisit::first()->page)->toBe('about');
});

it('registers visit using full URL when no route name', function (): void {
    makeAction()->handle(Request::create('http://localhost/contact'));

    expect(PageVisit::count())->toBe(1)
        ->and(PageVisit::first()->page)->toBe('http://localhost/contact');
});

it('creates a new row for each visit', function (): void {
    $request = Request::create('http://localhost/home');

    makeAction()->handle($request);
    makeAction()->handle($request);
    makeAction()->handle($request);

    expect(PageVisit::count())->toBe(3);
});

it('stores null user_id for guests', function (): void {
    makeAction()->handle(Request::create('http://localhost/home'));

    expect(PageVisit::first()->user_id)->toBeNull();
});

it('stores ip address from request', function (): void {
    $request = Request::create('http://localhost/home', 'GET', [], [], [], ['REMOTE_ADDR' => '192.168.1.1']);

    makeAction()->handle($request);

    expect(PageVisit::first()->ip_address)->toBe('192.168.1.1');
});

it('detects desktop device type', function (): void {
    $request = Request::create('http://localhost/home', 'GET', [], [], [], [
        'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
    ]);

    makeAction()->handle($request);

    expect(PageVisit::first()->device_type)->toBe('desktop');
});

it('detects mobile device type', function (): void {
    $request = Request::create('http://localhost/home', 'GET', [], [], [], [
        'HTTP_USER_AGENT' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X)',
    ]);

    makeAction()->handle($request);

    expect(PageVisit::first()->device_type)->toBe('mobile');
});

it('tracks multiple pages independently', function (): void {
    makeAction()->handle(Request::create('http://localhost/page-a'));
    makeAction()->handle(Request::create('http://localhost/page-a'));
    makeAction()->handle(Request::create('http://localhost/page-b'));

    expect(PageVisit::where('page', 'http://localhost/page-a')->count())->toBe(2)
        ->and(PageVisit::where('page', 'http://localhost/page-b')->count())->toBe(1);
});
