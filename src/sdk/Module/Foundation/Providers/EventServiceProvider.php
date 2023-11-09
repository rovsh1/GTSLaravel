<?php

namespace Sdk\Module\Foundation\Providers;

use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\Event\IntegrationEventPublisherInterface;
use Sdk\Module\Contracts\Event\IntegrationEventSubscriberInterface;
use Sdk\Module\Event\DomainEventDispatcher;
use Sdk\Module\Event\IntegrationEventPublisher;
use Sdk\Module\Event\IntegrationEventSubscriber;
use Sdk\Module\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(IntegrationEventPublisherInterface::class, IntegrationEventPublisher::class);
        $this->app->singleton(DomainEventDispatcherInterface::class, DomainEventDispatcher::class);
        $this->app->singleton(IntegrationEventSubscriberInterface::class, IntegrationEventSubscriber::class);
    }
}
