<?php

namespace Sdk\Module\Event;

use Sdk\Module\Contracts\Event\IntegrationEventInterface;
use Sdk\Module\Contracts\Event\IntegrationEventMessage;
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

    public function handle(IntegrationEventMessage $message): void
    {
        foreach ($this->listeners as $eventClass => $listeners) {
            if ($message->event === $eventClass) {
                $this->dispatchListeners($message, $listeners);
            }
        }
    }

    private function dispatchListeners(IntegrationEventMessage $message, array $listeners): void
    {
        foreach ($listeners as $listenerClass) {
            $listener = $this->container->get($listenerClass);

            //@todo debug mode
            try {
                $listener->handle($message);
            } catch (\Throwable $e) {
                dd($e);
            }
        }
    }
}