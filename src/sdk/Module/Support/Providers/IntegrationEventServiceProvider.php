<?php

namespace Sdk\Module\Support\Providers;

use Sdk\Module\Contracts\Event\IntegrationEventSubscriberInterface;
use Sdk\Module\Support\ServiceProvider;

class IntegrationEventServiceProvider extends ServiceProvider
{
    protected array $listen = [];

    protected array $listeners = [];

    public function register(): void
    {
        $this->app->resolving(IntegrationEventSubscriberInterface::class, function ($integrationEventSubscriber) {
            $this->registerListeners($integrationEventSubscriber);
        });
    }

    protected function registerListeners(IntegrationEventSubscriberInterface $integrationEventSubscriber): void
    {
        foreach ($this->listen as $eventClass => $listeners) {
            if (is_array($listeners)) {
                foreach ($listeners as $listener) {
                    $integrationEventSubscriber->listen($eventClass, $listener);
                }
            } else {
                $integrationEventSubscriber->listen($eventClass, $listeners);
            }
        }

        foreach ($this->listeners as $listener => $events) {
            if (is_array($events)) {
                foreach ($events as $event) {
                    $integrationEventSubscriber->listen($event, $listener);
                }
            } else {
                $integrationEventSubscriber->listen($events, $listener);
            }
        }
    }
}
