<?php

namespace Module\Support\IntegrationEventBus\Providers;

use Illuminate\Support\ServiceProvider;
use Module\Support\IntegrationEventBus\Service\MessageSender;

class BootServiceProvider extends ServiceProvider
{
    private array $availableModules = [
        'BookingEventSourcing',
        'BookingRequesting',
    ];

    public function boot(): void
    {
        $this->app->singleton(MessageSender::class, fn() => new MessageSender($this->availableModules));
    }
}
