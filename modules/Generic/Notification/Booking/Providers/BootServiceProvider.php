<?php

namespace Module\Generic\Notification\Booking\Providers;

use Module\Generic\NotificationManager\Providers\EventServiceProvider;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(EventServiceProvider::class);
    }
}
