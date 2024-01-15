<?php

namespace Pkg\IntegrationEventBus\Service;

use Pkg\IntegrationEventBus\Entity\Message;
use Pkg\Supplier\Traveline\Contracts\IntegrationEventDispatcherInterface as TravelineEventDispatcher;
use Sdk\Shared\Contracts\Event\IntegrationEventInterface;
use Sdk\Shared\Event\IntegrationEventMessage;

class MessageSender
{
    public function __construct(private readonly array $availableModules) {}

    public function send(Message $message): void
    {
        $triggerModule = $message->originator();
        $event = $this->makeIntegrationEvent($message);

        foreach ($this->availableModules as $name) {
            if ($name === $triggerModule) {
                continue;
            }
            $module = module($name);
            $module->boot();

            try {
                $module->dispatchEvent($event);
            } catch (\Throwable $e) {
                dd($e);
            }
        }
        app(TravelineEventDispatcher::class)->dispatch($event->event);
    }

    private function makeIntegrationEvent(Message $message): IntegrationEventMessage
    {
        $eventClass = $message->event();

        assert(is_subclass_of($eventClass, IntegrationEventInterface::class));

        return new IntegrationEventMessage(
            originator: $message->originator(),
            event: unserialize($message->payload(), ['allowed_classes' => true]),
            context: $message->context()
        );
    }
}
