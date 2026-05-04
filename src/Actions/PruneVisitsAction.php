<?php

declare(strict_types=1);

namespace Panchodp\LaravelPageMonitor\Actions;

use Panchodp\LaravelPageMonitor\Models\PageVisit;

final class PruneVisitsAction
{
    public function handle(): int
    {
        $pruned = 0;

        $retentionDays = config('laravel_page_monitor.pruning.retention_days');
        if (is_int($retentionDays) && $retentionDays > 0) {
            $deleted = PageVisit::where('visited_at', '<', now()->subDays($retentionDays))->delete();
            $pruned += is_int($deleted) ? $deleted : 0;
        }

        $maxRecords = config('laravel_page_monitor.pruning.max_records');
        if (is_int($maxRecords) && $maxRecords > 0) {
            $total = PageVisit::count();
            if ($total > $maxRecords) {
                $ids = PageVisit::oldest('visited_at')->limit($total - $maxRecords)->pluck('id');
                $deleted = PageVisit::whereIn('id', $ids)->delete();
                $pruned += is_int($deleted) ? $deleted : 0;
            }
        }

        return $pruned;
    }
}
