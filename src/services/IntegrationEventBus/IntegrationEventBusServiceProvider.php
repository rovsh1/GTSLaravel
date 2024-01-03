<?php

namespace Services\IntegrationEventBus;

use Illuminate\Support\ServiceProvider;
use Services\IntegrationEventBus\Service\MessageSender;

class IntegrationEventBusServiceProvider extends ServiceProvider
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
