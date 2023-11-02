<?php

namespace Module\Generic\Notification\Providers;

use Sdk\Module\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(AdapterServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $this->app->register(MailSettingsServiceProvider::class);
    }

    public function boot(): void
    {
    }
}
