<?php

namespace Module\Shared\Providers;

use Custom\Framework\Support\DateTime;
use Illuminate\Support\DateFactory;
use Illuminate\Support\ServiceProvider;

class DateServiceProvider extends ServiceProvider
{
    public function register()
    {
        DateTime::setFormats([
            'date' => 'd.m.Y',
            'time' => 'H:i',
            'datetime' => 'd.m.Y H:i'
        ]);

        DateFactory::useClass(DateTime::class);
    }

    public function boot() {}
}
