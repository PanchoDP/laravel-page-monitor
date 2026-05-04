<?php

declare(strict_types=1);

namespace Panchodp\LaravelPageMonitor\Actions;

use Panchodp\LaravelPageMonitor\Models\PageVisit;

final class RebootCountAction
{
    public function handle(): void
    {
        PageVisit::truncate();
    }
}
