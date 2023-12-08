<?php

namespace Sdk\Shared\Support\ServiceProvider;

use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\IntegrationEventPublisherInterface;
use Sdk\Module\Contracts\Support\ContainerInterface;
use Sdk\Module\Support\Providers\DomainEventServiceProvider as ServiceProvider;
use Sdk\Shared\Support\IntegrationEvent\SendIntegrationEventListener;

class DomainEventServiceProvider extends ServiceProvider
{
    protected array $listen = [];

    protected array $integrationEventMappers;

    public function register(): void
    {
        parent::register();

        if (!empty($this->integrationEventMappers)) {
            $this->bootIntegrationEventMappers();
        }
    }

    private function bootIntegrationEventMappers(): void
    {
        $this->app->singleton(SendIntegrationEventListener::class, function () {
            return new SendIntegrationEventListener(
                $this->app->get(ContainerInterface::class),
                $this->app->get(IntegrationEventPublisherInterface::class),
                $this->integrationEventMappers
            );
        });

        $this->app->resolving(DomainEventDispatcherInterface::class, function ($domainEventDispatcher) {
            $domainEventDispatcher->listen(DomainEventInterface::class, SendIntegrationEventListener::class);
        });
    }
}
