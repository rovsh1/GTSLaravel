<?php

namespace App\Admin\Services\JournalLogger;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class JournalLoggerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Event::listen('eloquent.*', JournalLoggerListener::class);
    }
}
