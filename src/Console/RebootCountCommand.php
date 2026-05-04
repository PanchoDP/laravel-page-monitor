<?php

declare(strict_types=1);

namespace Panchodp\LaravelPageMonitor\Console;

use Illuminate\Console\Command;
use Panchodp\LaravelPageMonitor\Actions\RebootCountAction;
use Throwable;

final class RebootCountCommand extends Command
{
    protected $signature = 'pagemonitor:reboot-count';

    protected $description = 'Reboot page monitor';

    public function handle(RebootCountAction $reboot_count_action): int
    {

        try {
            $reboot_count_action->handle();

            return 0;

        } catch (Throwable $e) {
            $this->error($e->getMessage());

            return 1;
        }

    }
}
