<?php

namespace Sdk\Shared\Support\Providers;

trait RegisterListenersTrait
{
    protected function registerListeners($domainEventDispatcher): void
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