<?php

namespace Module\Support\IntegrationEventBus\Service;

use App\Shared\Contracts\Module\ModuleAdapterInterface;
use Module\Support\IntegrationEventBus\Entity\Message;
use ReflectionClass;
use Sdk\Module\Contracts\Event\IntegrationEventInterface;

class MessageSender
{
    private array $availableModules = [
//        'Booking',
//        'Hotel',
        'Pricing',
        'Logging'
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

    private function makeIntegrationEvent(Message $message): IntegrationEventInterface
    {
        $eventClass = $message->event();
        $reflect = new ReflectionClass($eventClass);

        return $reflect->newInstanceArgs($message->eventPayload());
    }
}