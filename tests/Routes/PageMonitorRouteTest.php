<?php

declare(strict_types=1);

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use Panchodp\LaravelPageMonitor\Models\PageVisit;

beforeEach(function (): void {
    $user = User::forceCreate(['name' => 'Test', 'email' => 'test@example.com', 'password' => 'password']);
    $this->actingAs($user);
});

it('returns a 200 response', function (): void {
    $this->get('/page-monitor')->assertStatus(200);
});

it('shows the empty state when no visits are recorded', function (): void {
    $this->get('/page-monitor')->assertSee('No page visits recorded yet.');
});

it('displays recorded pages', function (): void {
    PageVisit::create(['page' => '/home', 'visited_at' => now()]);
    PageVisit::create(['page' => '/contact', 'visited_at' => now()]);

    $this->get('/page-monitor')
        ->assertSee('/home')
        ->assertSee('/contact');
});

it('displays visit date and time', function (): void {
    PageVisit::create(['page' => '/home', 'visited_at' => Carbon::create(2026, 5, 2, 14, 25, 0)]);

    $this->get('/page-monitor')->assertSee('02 May 2026, 14:25');
});

it('displays Guest when user_id is null', function (): void {
    PageVisit::create(['page' => '/home', 'user_id' => null, 'visited_at' => now()]);

    $this->get('/page-monitor')->assertSee('Guest');
});

it('route is named page-monitor', function (): void {
    expect(route('page-monitor'))->toContain('/page-monitor');
});

it('clears all visits and redirects when DELETE is called', function (): void {
    PageVisit::create(['page' => '/home', 'visited_at' => now()]);
    PageVisit::create(['page' => '/about', 'visited_at' => now()]);

    $this->delete('/page-monitor')
        ->assertRedirect('/page-monitor');

    expect(PageVisit::count())->toBe(0);
});

it('displays the clear button', function (): void {
    $this->get('/page-monitor')->assertSee('Clear');
});

it('paginates visits and shows only the first page', function (): void {
    foreach (range(1, 55) as $i) {
        PageVisit::create(['page' => "/page-{$i}", 'visited_at' => now()->subSeconds($i)]);
    }
    config(['laravel_page_monitor.per_page' => 50]);

    $this->get('/page-monitor')->assertSee('/page-1');         // más reciente, en página 1
    $this->get('/page-monitor')->assertDontSee('/page-55');    // más antiguo, en página 2
});
