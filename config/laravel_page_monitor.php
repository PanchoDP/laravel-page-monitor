<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | LaravelPageMonitor Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration options for the LaravelPageMonitor package.
    |
    */

    'enabled' => env('MONITOR_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    |
    | The model used to associate authenticated visits. Change this if your
    | application uses a custom User model.
    |
    */

    'user_model' => env('PAGE_MONITOR_USER_MODEL', 'App\Models\User'),

    /*
    |--------------------------------------------------------------------------
    | Dashboard Middleware
    |--------------------------------------------------------------------------
    |
    | Middleware applied to the /page-monitor dashboard route. Add or replace
    | these to control who can access it. For fine-grained control, define the
    | 'view-page-monitor' gate in your AppServiceProvider.
    |
    */

    'middleware' => ['web', 'auth'],

    /*
    |--------------------------------------------------------------------------
    | Track All Pages
    |--------------------------------------------------------------------------
    |
    | When enabled, all routes in the 'web' middleware group are tracked
    | automatically. No need to add the 'visits-count' middleware manually.
    | The /page-monitor dashboard route is always excluded.
    |
    */

    'track_all' => env('MONITOR_TRACK_ALL', false),

    /*
    |--------------------------------------------------------------------------
    | Data Pruning
    |--------------------------------------------------------------------------
    |
    | Controls automatic cleanup of old visit records. Run the prune command
    | on a schedule: Schedule::command('pagemonitor:prune')->daily();
    |
    | retention_days: Delete records older than this many days. null = disabled.
    | max_records: Delete oldest records when total exceeds this limit. null = disabled.
    |
    */

    'pruning' => [
        'retention_days' => env('MONITOR_RETENTION_DAYS', 30),
        'max_records' => env('MONITOR_MAX_RECORDS', 10000),
    ],

];
