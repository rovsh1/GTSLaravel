<?php

namespace Sdk\Module\Event;

use Sdk\Module\Contracts\Event\IntegrationEventInterface;
use Sdk\Module\Contracts\Event\IntegrationEventSubscriberInterface;
use Sdk\Module\Contracts\Support\ContainerInterface;

class IntegrationEventSubscriber implements IntegrationEventSubscriberInterface
{
    protected array $listeners = [];

    public function __construct(
        private readonly ContainerInterface $container
    ) {
    }

    public function listen(string $eventClass, string $listenerClass): void
    {
        if (!isset($this->listeners[$eventClass])) {
            $this->listeners[$eventClass] = [];
        }
        $this->listeners[$eventClass][] = $listenerClass;
    }

    public function handle(IntegrationEventInterface $event): void
    {
        foreach ($this->listeners as $eventClass => $listeners) {
            if ($event::class === $eventClass || is_subclass_of($event, $eventClass)) {
                $this->dispatchListeners($event, $listeners);
            }
        }
    }

    private function dispatchListeners(IntegrationEventInterface $event, array $listeners): void
    {
        foreach ($listeners as $listenerClass) {
            $listener = $this->container->get($listenerClass);

            $listener->handle($event);
        }
    }
}