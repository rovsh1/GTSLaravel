<?php

namespace Custom\Framework\Event;

use Custom\Framework\Contracts\Event\DomainEventDispatcherInterface;
use Custom\Framework\Contracts\Event\DomainEventHandlerInterface;
use Custom\Framework\Contracts\Event\DomainEventInterface;
use Custom\Framework\Foundation\Module;

class DomainEventDispatcher implements DomainEventDispatcherInterface
{
    private array $listeners = [];

    public function __construct(
        private readonly Module $module,
        private readonly DomainEventHandlerInterface $domainEventHandler
    ) {}

    public function listen(string $eventClass, string $listenerClass)
    {
        if (!isset($this->listeners[$eventClass])) {
            $this->listeners[$eventClass] = [];
        }
        $this->listeners[$eventClass][] = $listenerClass;
    }

    public function dispatch(DomainEventInterface $event): void
    {
        /*foreach ($this->middlewareHandlers as $middlewareHandler) {
            $middlewareHandler($command);
        }*/
//        $this->dispatchApplicationListener($event);

        foreach ($this->listeners as $eventClass => $listeners) {
            if (is_subclass_of($event, $eventClass)) {
                $this->dispatchListeners($event, $listeners);
            }
        }

        $this->dispatchGlobalListeners($event);

        $this->domainEventHandler->handle($event);
    }

    private function dispatchGlobalListeners(DomainEventInterface $event)
    {
        if (!isset($this->listeners['*'])) {
            return;
        }

        $this->dispatchListeners($event, $this->listeners['*']);
    }

    private function dispatchListeners(DomainEventInterface $event, array $listeners)
    {
        foreach ($listeners as $listenerClass) {
            $listener = $this->module->make($listenerClass);
            $listener->handle($event);
        }
    }
}
