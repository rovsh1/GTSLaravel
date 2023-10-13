<?php

namespace Sdk\Module\Event;

use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventPublisherInterface;
use Sdk\Module\Contracts\ModuleInterface;

class DomainEventDispatcher implements DomainEventDispatcherInterface
{
    private array $listeners = [];

    public function __construct(
        private readonly ModuleInterface $module,
        private readonly DomainEventPublisherInterface $domainEventPublisher
    ) {
    }

    public function listen(string $eventClass, string $listenerClass): void
    {
        if (!isset($this->listeners[$eventClass])) {
            $this->listeners[$eventClass] = [];
        }
        $this->listeners[$eventClass][] = $listenerClass;
    }

    public function dispatch(DomainEventInterface ...$events): void
    {
        foreach ($events as $event) {
            $this->dispatchEvent($event);
        }

        $this->domainEventPublisher->publish(...$events);
    }

    private function dispatchEvent(DomainEventInterface $event): void
    {
        /*foreach ($this->middlewareHandlers as $middlewareHandler) {
            $middlewareHandler($command);
        }*/
//        $this->dispatchApplicationListener($event);

        foreach ($this->listeners as $eventClass => $listeners) {
            if ($event::class === $eventClass || is_subclass_of($event, $eventClass)) {
                $this->dispatchListeners($event, $listeners);
            }
        }

        $this->dispatchGlobalListeners($event);
//        $this->domainEventHandler->handle($event);
    }

    private function dispatchGlobalListeners(DomainEventInterface $event): void
    {
        if (!isset($this->listeners['*'])) {
            return;
        }

        $this->dispatchListeners($event, $this->listeners['*']);
    }

    private function dispatchListeners(DomainEventInterface $event, array $listeners): void
    {
        foreach ($listeners as $listenerClass) {
            $listener = $this->module->get($listenerClass);

            $listener->handle($event);
        }
    }
}
