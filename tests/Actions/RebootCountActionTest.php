<?php

declare(strict_types=1);

use Panchodp\LaravelPageMonitor\Actions\RebootCountAction;
use Panchodp\LaravelPageMonitor\Models\PageVisit;

it('clears all visit records from the database', function (): void {
    PageVisit::create(['page' => '/home', 'visited_at' => now()]);
    PageVisit::create(['page' => '/about', 'visited_at' => now()]);

    (new RebootCountAction)->handle();

    expect(PageVisit::count())->toBe(0);
});

it('is idempotent when table is already empty', function (): void {
    (new RebootCountAction)->handle();

    expect(PageVisit::count())->toBe(0);
});
