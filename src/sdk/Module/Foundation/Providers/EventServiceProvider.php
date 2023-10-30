<?php

namespace Sdk\Module\Foundation\Providers;

use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\Event\DomainEventPublisherInterface;
use Sdk\Module\Contracts\Event\IntegrationEventSubscriberInterface;
use Sdk\Module\Event\DomainEventDispatcher;
use Sdk\Module\Event\DomainEventPublisher;
use Sdk\Module\Event\IntegrationEventSubscriber;
use Sdk\Module\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(DomainEventPublisherInterface::class, DomainEventPublisher::class);
        $this->app->singleton(DomainEventDispatcherInterface::class, DomainEventDispatcher::class);
        $this->app->singleton(IntegrationEventSubscriberInterface::class, IntegrationEventSubscriber::class);
    }
}
