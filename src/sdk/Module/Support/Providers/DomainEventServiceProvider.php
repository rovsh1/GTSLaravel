<?php

namespace Sdk\Module\Support\Providers;

use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\Event\DomainEventPublisherInterface;
use Sdk\Module\Support\ServiceProvider;

class DomainEventServiceProvider extends ServiceProvider
{
    protected array $listen = [];

    protected string $integrationEventMapper;

    public function register(): void
    {
        $this->app->resolving(DomainEventDispatcherInterface::class, function ($domainEventDispatcher) {
            $this->registerListeners($domainEventDispatcher);
        });
        $this->app->resolving(DomainEventPublisherInterface::class, function ($domainEventPublisher) {
            $this->bootPublisher($domainEventPublisher);
        });
    }

    protected function registerListeners(DomainEventDispatcherInterface $domainEventDispatcher): void
    {
        foreach ($this->listen as $eventClass => $listeners) {
            if (is_array($listeners)) {
                foreach ($listeners as $listener) {
                    $domainEventDispatcher->listen($eventClass, $listener);
                }
            } else {
                $domainEventDispatcher->listen($eventClass, $listeners);
            }
        }
    }

    protected function bootPublisher(DomainEventPublisherInterface $domainEventPublisher): void
    {
        if (!isset($this->integrationEventMapper)) {
            return;
        }

        $domainEventPublisher->registerMapper($this->app->make($this->integrationEventMapper));
    }
}
