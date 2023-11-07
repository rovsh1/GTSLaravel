<?php

namespace Module\Booking\Moderation\Providers;

use Sdk\Module\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        //TODO временное, убрать в shared
        $this->app->register(\Module\Booking\Shared\Providers\BootServiceProvider::class);
    }
}
