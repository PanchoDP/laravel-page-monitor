<?php

declare(strict_types=1);

namespace Panchodp\LaravelPageMonitor\Actions;

use Illuminate\Database\Eloquent\Collection;
use Panchodp\LaravelPageMonitor\Models\PageVisit;

final class BuildPageMatrixAction
{
    /** @return Collection<int, PageVisit> */
    public function handle(): Collection
    {
        $query = PageVisit::query()->latest('visited_at');

        $userModel = config('laravel_page_monitor.user_model');
        if (is_string($userModel) && class_exists($userModel)) {
            $query->with('user');
        }

        return $query->get();
    }
}
