<?php

declare(strict_types=1);

use Panchodp\LaravelPageMonitor\Actions\PruneVisitsAction;
use Panchodp\LaravelPageMonitor\Models\PageVisit;

it('deletes records older than retention_days', function (): void {
    PageVisit::create(['page' => '/old', 'visited_at' => now()->subDays(31)]);
    PageVisit::create(['page' => '/recent', 'visited_at' => now()]);

    config(['laravel_page_monitor.pruning.retention_days' => 30, 'laravel_page_monitor.pruning.max_records' => null]);

    (new PruneVisitsAction)->handle();

    expect(PageVisit::count())->toBe(1)
        ->and(PageVisit::first()->page)->toBe('/recent');
});

it('keeps records within the retention period', function (): void {
    PageVisit::create(['page' => '/a', 'visited_at' => now()->subDays(10)]);
    PageVisit::create(['page' => '/b', 'visited_at' => now()->subDays(20)]);

    config(['laravel_page_monitor.pruning.retention_days' => 30, 'laravel_page_monitor.pruning.max_records' => null]);

    (new PruneVisitsAction)->handle();

    expect(PageVisit::count())->toBe(2);
});

it('prunes oldest records when max_records is exceeded', function (): void {
    PageVisit::create(['page' => '/oldest', 'visited_at' => now()->subHours(3)]);
    PageVisit::create(['page' => '/middle', 'visited_at' => now()->subHours(2)]);
    PageVisit::create(['page' => '/newest', 'visited_at' => now()->subHour()]);

    config(['laravel_page_monitor.pruning.retention_days' => null, 'laravel_page_monitor.pruning.max_records' => 2]);

    (new PruneVisitsAction)->handle();

    expect(PageVisit::count())->toBe(2)
        ->and(PageVisit::orderBy('visited_at')->first()->page)->toBe('/middle');
});

it('does not prune when total is within max_records', function (): void {
    PageVisit::create(['page' => '/a', 'visited_at' => now()]);
    PageVisit::create(['page' => '/b', 'visited_at' => now()]);

    config(['laravel_page_monitor.pruning.retention_days' => null, 'laravel_page_monitor.pruning.max_records' => 10]);

    (new PruneVisitsAction)->handle();

    expect(PageVisit::count())->toBe(2);
});

it('returns the number of pruned records', function (): void {
    PageVisit::create(['page' => '/old', 'visited_at' => now()->subDays(31)]);
    PageVisit::create(['page' => '/recent', 'visited_at' => now()]);

    config(['laravel_page_monitor.pruning.retention_days' => 30, 'laravel_page_monitor.pruning.max_records' => null]);

    $pruned = (new PruneVisitsAction)->handle();

    expect($pruned)->toBe(1);
});

it('does nothing when both limits are null', function (): void {
    PageVisit::create(['page' => '/a', 'visited_at' => now()->subDays(100)]);
    PageVisit::create(['page' => '/b', 'visited_at' => now()]);

    config(['laravel_page_monitor.pruning.retention_days' => null, 'laravel_page_monitor.pruning.max_records' => null]);

    (new PruneVisitsAction)->handle();

    expect(PageVisit::count())->toBe(2);
});
