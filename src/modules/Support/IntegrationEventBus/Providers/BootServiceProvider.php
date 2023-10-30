<?php

namespace Module\Support\IntegrationEventBus\Providers;

use Illuminate\Support\ServiceProvider;
use Module\Support\IntegrationEventBus\Service\MessageSender;

class BootServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(MessageSender::class);
    }
}
