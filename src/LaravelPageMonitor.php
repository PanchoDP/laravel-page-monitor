<?php

declare(strict_types=1);

namespace Panchodp\LaravelPageMonitor;

use Illuminate\Database\Eloquent\Collection;
use Panchodp\LaravelPageMonitor\Models\PageVisit;

final class LaravelPageMonitor
{
    /** @return Collection<int, PageVisit> */
    public function ranking(): Collection
    {
        return PageVisit::query()
            ->selectRaw('page, COUNT(*) as visits, MAX(visited_at) as last_visit')
            ->groupBy('page')
            ->orderByDesc('visits')
            ->get();
    }

    /** @return Collection<int, PageVisit> */
    public function byNameOrder(bool $desc = false): Collection
    {
        return PageVisit::query()
            ->selectRaw('page, COUNT(*) as visits, MAX(visited_at) as last_visit')
            ->groupBy('page')
            ->orderBy('page', $desc ? 'desc' : 'asc')
            ->get();
    }
}
