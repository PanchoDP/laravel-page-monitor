<?php

declare(strict_types=1);

use Panchodp\LaravelPageMonitor\Models\PageVisit;

it('clears visit records via artisan command', function (): void {
    PageVisit::create(['page' => '/home', 'visited_at' => now()]);

    $this->artisan('pagemonitor:reboot-count')->assertExitCode(0);

    expect(PageVisit::count())->toBe(0);
});

it('returns exit code 0 on success', function (): void {
    $this->artisan('pagemonitor:reboot-count')->assertExitCode(0);
});
