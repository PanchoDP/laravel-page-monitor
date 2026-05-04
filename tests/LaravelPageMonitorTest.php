<?php

declare(strict_types=1);

use Panchodp\LaravelPageMonitor\LaravelPageMonitor;
use Panchodp\LaravelPageMonitor\Models\PageVisit;

it('ranking returns empty collection when no visits', function (): void {
    expect((new LaravelPageMonitor)->ranking())->toBeEmpty();
});

it('ranking returns pages ordered by visit count descending', function (): void {
    PageVisit::create(['page' => '/about', 'visited_at' => now()]);
    PageVisit::create(['page' => '/home', 'visited_at' => now()]);
    PageVisit::create(['page' => '/home', 'visited_at' => now()]);
    PageVisit::create(['page' => '/contact', 'visited_at' => now()]);
    PageVisit::create(['page' => '/contact', 'visited_at' => now()]);
    PageVisit::create(['page' => '/contact', 'visited_at' => now()]);

    $result = (new LaravelPageMonitor)->ranking();

    expect($result->pluck('page')->toArray())->toBe(['/contact', '/home', '/about']);
});

it('byNameOrder returns pages sorted alphabetically ascending', function (): void {
    PageVisit::create(['page' => '/zebra', 'visited_at' => now()]);
    PageVisit::create(['page' => '/apple', 'visited_at' => now()]);
    PageVisit::create(['page' => '/mango', 'visited_at' => now()]);

    $result = (new LaravelPageMonitor)->byNameOrder();

    expect($result->pluck('page')->toArray())->toBe(['/apple', '/mango', '/zebra']);
});

it('byNameOrder returns pages sorted alphabetically descending', function (): void {
    PageVisit::create(['page' => '/zebra', 'visited_at' => now()]);
    PageVisit::create(['page' => '/apple', 'visited_at' => now()]);
    PageVisit::create(['page' => '/mango', 'visited_at' => now()]);

    $result = (new LaravelPageMonitor)->byNameOrder(desc: true);

    expect($result->pluck('page')->toArray())->toBe(['/zebra', '/mango', '/apple']);
});
