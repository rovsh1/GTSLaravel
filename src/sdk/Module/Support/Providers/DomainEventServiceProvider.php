<?php

namespace Sdk\Module\Support\Providers;

use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Support\ServiceProvider;

class DomainEventServiceProvider extends ServiceProvider
{
    protected array $listen = [];

    protected array $listeners = [];

    public function register(): void
    {
        $this->app->resolving(DomainEventDispatcherInterface::class, function ($domainEventDispatcher) {
            $this->registerListeners($domainEventDispatcher);
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

        foreach ($this->listeners as $listener => $events) {
            if (is_array($events)) {
                foreach ($events as $event) {
                    $domainEventDispatcher->listen($event, $listener);
                }
            } else {
                $domainEventDispatcher->listen($events, $listener);
            }
        }
    }
}
