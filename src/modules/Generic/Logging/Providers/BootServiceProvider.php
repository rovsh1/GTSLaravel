<?php

namespace Module\Generic\Logging\Providers;

use Sdk\Module\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(IntegrationEventServiceProvider::class);
    }
}
