<?php

declare(strict_types=1);

use Panchodp\LaravelPageMonitor\Actions\BuildPageMatrixAction;
use Panchodp\LaravelPageMonitor\Models\PageVisit;

it('returns empty collection when no visits', function (): void {
    expect((new BuildPageMatrixAction)->handle())->toBeEmpty();
});

it('returns visits ordered by most recent first', function (): void {
    PageVisit::create(['page' => '/old', 'visited_at' => now()->subHour()]);
    PageVisit::create(['page' => '/new', 'visited_at' => now()]);

    $result = (new BuildPageMatrixAction)->handle();

    expect($result->getCollection()->first()->page)->toBe('/new');
});

it('eager loads user relationship', function (): void {
    PageVisit::create(['page' => '/home', 'visited_at' => now()]);

    $result = (new BuildPageMatrixAction)->handle();

    expect($result->getCollection()->first()->relationLoaded('user'))->toBeTrue();
});
