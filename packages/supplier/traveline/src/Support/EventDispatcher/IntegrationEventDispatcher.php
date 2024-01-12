<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\Support\EventDispatcher;

use Pkg\Supplier\Traveline\Contracts\IntegrationEventDispatcherInterface;
use Sdk\Shared\Contracts\Event\IntegrationEventInterface;

class IntegrationEventDispatcher implements IntegrationEventDispatcherInterface
{
    protected array $listeners = [];

    public function listen(string $eventClass, string $listenerClass): void
    {
        if (!isset($this->listeners[$eventClass])) {
            $this->listeners[$eventClass] = [];
        }
        $this->listeners[$eventClass][] = $listenerClass;
    }

    public function dispatch(IntegrationEventInterface $event): void
    {
        foreach ($this->listeners as $eventClass => $listeners) {
            if (is_a($event, $eventClass)) {
                $this->dispatchListeners($event, $listeners);
            }
        }
    }

    private function dispatchListeners(IntegrationEventInterface $event, array $listeners): void
    {
        foreach ($listeners as $listenerClass) {
            $listener = app()->make($listenerClass);

            $listener->handle($event);
        }
    }
}
