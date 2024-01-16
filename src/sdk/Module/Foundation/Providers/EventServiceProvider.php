<?php

namespace Sdk\Module\Foundation\Providers;

use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Event\DomainEventDispatcher;
use Sdk\Module\Event\IntegrationEventSubscriber;
use Illuminate\Support\ServiceProvider;
use Sdk\Shared\Contracts\Event\IntegrationEventSubscriberInterface;

class EventServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(DomainEventDispatcherInterface::class, DomainEventDispatcher::class);
        $this->app->singleton(IntegrationEventSubscriberInterface::class, IntegrationEventSubscriber::class);
    }
}
