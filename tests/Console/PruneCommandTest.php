<?php

declare(strict_types=1);

use Panchodp\LaravelPageMonitor\Models\PageVisit;

beforeEach(function (): void {
    config(['laravel_page_monitor.pruning.retention_days' => 30, 'laravel_page_monitor.pruning.max_records' => null]);
});

it('prunes old records via artisan command', function (): void {
    PageVisit::create(['page' => '/old', 'visited_at' => now()->subDays(31)]);
    PageVisit::create(['page' => '/recent', 'visited_at' => now()]);

    $this->artisan('pagemonitor:prune')->assertExitCode(0);

    expect(PageVisit::count())->toBe(1);
});

it('outputs the number of pruned records', function (): void {
    PageVisit::create(['page' => '/old', 'visited_at' => now()->subDays(31)]);

    $this->artisan('pagemonitor:prune')
        ->expectsOutput('Pruned 1 page visit record(s).')
        ->assertExitCode(0);
});

it('returns exit code 0 when nothing to prune', function (): void {
    $this->artisan('pagemonitor:prune')->assertExitCode(0);
});
