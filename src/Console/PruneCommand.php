<?php

declare(strict_types=1);

namespace Panchodp\LaravelPageMonitor\Console;

use Illuminate\Console\Command;
use Panchodp\LaravelPageMonitor\Actions\PruneVisitsAction;
use Throwable;

final class PruneCommand extends Command
{
    protected $signature = 'pagemonitor:prune';

    protected $description = 'Prune old page visit records based on retention settings';

    public function handle(PruneVisitsAction $prune): int
    {
        try {
            $pruned = $prune->handle();
            $this->info("Pruned {$pruned} page visit record(s).");

            return 0;
        } catch (Throwable $e) {
            $this->error($e->getMessage());

            return 1;
        }
    }
}
