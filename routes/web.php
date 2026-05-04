<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Panchodp\LaravelPageMonitor\Actions\BuildPageMatrixAction;

Route::get('page-monitor', function (BuildPageMatrixAction $action) {
    $visits = $action->handle();

    return view('laravel-page-monitor::page-monitor', compact('visits'));
})->middleware(config('laravel_page_monitor.middleware', ['web', 'auth']))
    ->middleware('can:view-page-monitor')
    ->name('page-monitor');
