<?php

namespace App\Core\Providers;

use Illuminate\Support\DateFactory;
use Illuminate\Support\ServiceProvider;
use Sdk\Module\Support\DateTime;

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
