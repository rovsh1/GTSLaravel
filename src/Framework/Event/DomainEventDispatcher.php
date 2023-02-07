<?php

namespace Custom\Framework\Event;

use Illuminate\Contracts\Container\Container;

class DomainEventDispatcher implements DomainEventDispatcherInterface
{
    private array $listeners = [];

    public function __construct(
        private readonly Container $container,
        private readonly IntegrationEventDispatcher $integrationEventsDispatcher
    ) {}

    public function listen(string $eventClass, string $listenerClass)
    {
        if (!$this->listeners[$eventClass])
            $this->listeners[$eventClass] = [];
        $this->listeners[$eventClass][] = $listenerClass;
    }

    public function dispatch(DomainEventInterface $event): void
    {
        /*foreach ($this->middlewareHandlers as $middlewareHandler) {
            $middlewareHandler($command);
        }*/
        $this->dispatchApplicationListener($event);

        foreach ($this->listeners as $eventClass => $listeners) {
            if (is_subclass_of($event, $eventClass))
                $this->dispatchListeners($event, $listeners);
        }

        $this->dispatchGlobalListeners($event);

        $this->integrationEventsDispatcher->dispatch($event);
    }

    private function dispatchGlobalListeners(DomainEventInterface $event)
    {
        if (!isset($this->listeners['*']))
            return;

        $this->dispatchListeners($event, $this->listeners['*']);
    }

    private function dispatchListeners(DomainEventInterface $event, array $listeners)
    {
        foreach ($listeners as $listenerClass) {
            $listener = $this->container->make($listenerClass);
            $listener->handle($event);
        }
    }

    private function dispatchApplicationListener(DomainEventInterface $event)
    {
        $listenerClass = str_replace('Domain', 'Application', $event::class) . 'Listener';
        if (!class_exists($listenerClass))
            return;

        $listener = $this->container->make($listenerClass);
        $listener->handle($event);
    }
}
