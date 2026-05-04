<?php

declare(strict_types=1);

namespace Panchodp\LaravelPageMonitor;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Panchodp\LaravelPageMonitor\Console\PruneCommand;
use Panchodp\LaravelPageMonitor\Console\RebootCountCommand;
use Panchodp\LaravelPageMonitor\Http\Middlewares\VisitsCountMiddleware;

final class LaravelPageMonitorServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[\Override]
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/laravel_page_monitor.php',
            'laravel_page_monitor'
        );
        $this->app->singleton('monitor', LaravelPageMonitor::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                RebootCountCommand::class,
                PruneCommand::class,
            ]);

            $this->publishes([
                __DIR__.'/../config/laravel_page_monitor.php' => config_path('laravel_page_monitor.php'),
            ], 'laravel-page-monitor-config');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-page-monitor'),
            ], 'laravel-page-monitor-views');

            $this->publishes([
                __DIR__.'/../resources/css' => public_path('vendor/laravel-page-monitor/css'),
                __DIR__.'/../resources/js' => public_path('vendor/laravel-page-monitor/js'),
            ], 'laravel-page-monitor-assets');

            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'laravel-page-monitor-migrations');
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-page-monitor');

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        Gate::define('view-page-monitor', fn (): true => true);

        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('visits-count', VisitsCountMiddleware::class);

        if (config('laravel_page_monitor.track_all', false)) {
            $router->pushMiddlewareToGroup('web', VisitsCountMiddleware::class);
        }
    }
}
