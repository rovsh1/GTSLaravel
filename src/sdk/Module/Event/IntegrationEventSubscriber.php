<?php

namespace Sdk\Module\Event;

use Sdk\Module\Contracts\Support\ContainerInterface;
use Sdk\Shared\Contracts\Event\IntegrationEventSubscriberInterface;
use Sdk\Shared\Event\IntegrationEventMessage;

class IntegrationEventSubscriber implements IntegrationEventSubscriberInterface
{
    protected array $listeners = [];

    public function __construct(
        private readonly ContainerInterface $container
    ) {}

    public function listen(string $eventClass, string $listenerClass): void
    {
        if (!isset($this->listeners[$eventClass])) {
            $this->listeners[$eventClass] = [];
        }
        $this->listeners[$eventClass][] = $listenerClass;
    }

    public function handle(IntegrationEventMessage $message): void
    {
        foreach ($this->listeners as $eventClass => $listeners) {
            if (is_a($message->event, $eventClass)) {
                $this->dispatchListeners($message, $listeners);
            }
        }
    }

    private function dispatchListeners(IntegrationEventMessage $message, array $listeners): void
    {
        foreach ($listeners as $listenerClass) {
            $listener = $this->container->get($listenerClass);

            $listener->handle($message);
        }
    }
}