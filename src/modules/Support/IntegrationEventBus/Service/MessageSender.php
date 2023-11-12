<?php

namespace Module\Support\IntegrationEventBus\Service;

use App\Shared\Contracts\Module\ModuleAdapterInterface;
use Module\Support\IntegrationEventBus\Entity\Message;
use Sdk\Module\Contracts\Event\IntegrationEventMessage;

class MessageSender
{
    private array $availableModules = [
        'BookingEventSourcing'
    ];

    public function send(Message $message): void
    {
        $triggerModule = $message->module();
        $event = $this->makeIntegrationEvent($message);

        /** @var ModuleAdapterInterface $module */
        foreach (app()->modules() as $module) {
            if ($module->is($triggerModule) || !in_array($module->name(), $this->availableModules)) {
                continue;
            }

            $module->boot();

            $module->dispatchEvent($event);
        }
    }

    private function makeIntegrationEvent(Message $message): IntegrationEventMessage
    {
        return new IntegrationEventMessage(
            module: $message->module(),
            event: $message->event(),
            payload: $message->payload(),
            context: []
        );
    }
}