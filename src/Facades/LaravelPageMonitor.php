<?php

declare(strict_types=1);

namespace Panchodp\LaravelPageMonitor\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Database\Eloquent\Collection<int, \Panchodp\LaravelPageMonitor\Models\PageVisit> ranking()
 * @method static \Illuminate\Database\Eloquent\Collection<int, \Panchodp\LaravelPageMonitor\Models\PageVisit> byNameOrder(bool $desc = false)
 *
 * @see \Panchodp\LaravelPageMonitor\LaravelPageMonitor
 */
final class LaravelPageMonitor extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'monitor';
    }
}
