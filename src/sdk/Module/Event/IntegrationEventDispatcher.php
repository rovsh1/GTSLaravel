<?php

namespace Sdk\Module\Event;

use Sdk\Module\Contracts\Event\IntegrationEventDispatcherInterface;
use Sdk\Module\Contracts\Event\IntegrationEventInterface;
use Sdk\Module\Foundation\Module;

class IntegrationEventDispatcher implements IntegrationEventDispatcherInterface
{
    private array $listeners = [];

    public function __construct(private readonly Module $module) {}

    public function listen(string $eventClass, string $listenerClass)
    {
        if (!isset($this->listeners[$eventClass])) {
            $this->listeners[$eventClass] = [];
        }
        $this->listeners[$eventClass][] = $listenerClass;
    }

    public function dispatch(IntegrationEventInterface $event): void
    {
        $this->dispatchListeners($event, $this->listeners[$event->key()] ?? []);

        $this->dispatchGlobalListeners($event);
    }

    private function dispatchGlobalListeners(IntegrationEventInterface $event)
    {
        if (!isset($this->listeners['*'])) {
            return;
        }

        $this->dispatchListeners($event, $this->listeners['*']);
    }

    private function dispatchListeners(\Sdk\Module\Contracts\Event\IntegrationEventInterface $event, array $listeners)
    {
        foreach ($listeners as $listenerClass) {
            $listener = $this->module->make($listenerClass);
            $listener->handle($event);
        }
    }
}
