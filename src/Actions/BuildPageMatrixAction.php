<?php

declare(strict_types=1);

namespace Panchodp\LaravelPageMonitor\Actions;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Panchodp\LaravelPageMonitor\Models\PageVisit;

final class BuildPageMatrixAction
{
    /** @return LengthAwarePaginator<PageVisit> */
    public function handle(): LengthAwarePaginator
    {
        $query = PageVisit::query()->latest('visited_at');

        $userModel = config('laravel_page_monitor.user_model');
        if (is_string($userModel) && class_exists($userModel)) {
            $query->with('user');
        }

        return $query->paginate(config('laravel_page_monitor.per_page', 50));
    }
}
