<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Panchodp\LaravelPageMonitor\Actions\BuildPageMatrixAction;
use Panchodp\LaravelPageMonitor\Actions\RebootCountAction;

Route::get('page-monitor', function (BuildPageMatrixAction $action) {
    $visits = $action->handle();

    return view('laravel-page-monitor::page-monitor', compact('visits'));
})->middleware(config('laravel_page_monitor.middleware', ['web', 'auth']))
    ->middleware('can:view-page-monitor')
    ->name('page-monitor');

Route::delete('page-monitor', function (RebootCountAction $action) {
    $action->handle();

    return redirect()->route('page-monitor');
})->middleware(config('laravel_page_monitor.middleware', ['web', 'auth']))
    ->middleware('can:view-page-monitor')
    ->name('page-monitor.clear');
