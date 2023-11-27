<?php

namespace App\Admin\Services\ChangeLogger;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class ChangeLoggerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Event::listen('eloquent.*', ChangeLoggerListener::class);
    }
}
