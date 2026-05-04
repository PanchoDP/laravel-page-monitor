<?php

declare(strict_types=1);

namespace Panchodp\LaravelPageMonitor\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class PageVisit extends Model
{
    public $timestamps = false;

    protected $table = 'page_visits';

    protected $fillable = ['page', 'user_id', 'session_id', 'ip_address', 'device_type', 'user_agent', 'visited_at'];

    protected $casts = ['visited_at' => 'datetime'];

    public function user(): BelongsTo
    {
        /** @var class-string<Model> $userModel */
        $userModel = config('laravel_page_monitor.user_model');

        return $this->belongsTo($userModel);
    }
}
