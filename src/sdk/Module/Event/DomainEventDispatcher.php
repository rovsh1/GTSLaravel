<?php

namespace Sdk\Module\Event;

use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\HasIntegrationEventInterface;
use Sdk\Module\Contracts\ModuleInterface;
use Sdk\Module\Contracts\Support\ContainerInterface;
use Sdk\Shared\Contracts\Context\ContextInterface;
use Sdk\Shared\Contracts\Event\IntegrationEventPublisherInterface;

class DomainEventDispatcher implements DomainEventDispatcherInterface
{
    private array $listeners = [];

    public function __construct(
        private readonly ModuleInterface $module,
        private readonly ContainerInterface $container,
        private readonly IntegrationEventPublisherInterface $integrationEventPublisher,
        private readonly ContextInterface $context,
    ) {}

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

        $this->publish(...$events);
    }

    private function dispatchEvent(DomainEventInterface $event): void
    {
        /*foreach ($this->middlewareHandlers as $middlewareHandler) {
            $middlewareHandler($command);
        }*/
//        $this->dispatchApplicationListener($event);

        foreach ($this->listeners as $eventClass => $listeners) {
            if (is_a($event, $eventClass)) {
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
            $listener = $this->container->get($listenerClass);

            $listener->handle($event);
        }
    }

    /**
     * @param DomainEventInterface ...$events
     * @return void
     * @deprecated
     */
    private function publish(DomainEventInterface ...$events): void
    {
        $integrationEvents = array_map(
            fn(HasIntegrationEventInterface $e) => $e->integrationEvent(),
            array_filter($events, fn($e) => $e instanceof HasIntegrationEventInterface)
        );
        $context = $this->context->toArray();
        foreach ($integrationEvents as $event) {
            $this->integrationEventPublisher->publish($this->module->name(), $event, $context);
        }
    }
}
