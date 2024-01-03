<?php

namespace Services\IntegrationEventBus\Service;

use App\Shared\Contracts\Module\ModuleAdapterInterface;
use Sdk\Module\Contracts\Event\IntegrationEventInterface;
use Sdk\Module\Event\IntegrationEventMessage;
use Services\IntegrationEventBus\Entity\Message;

class MessageSender
{
    public function __construct(private readonly array $availableModules) {}

    public function send(Message $message): void
    {
        $triggerModule = $message->module();
        $event = $this->makeIntegrationEvent($message);

        /** @var ModuleAdapterInterface $module */
        foreach (app()->modules() as $module) {
            if ($module->is($triggerModule) || !$this->isDispatchableModule($module)) {
                continue;
            }

            $module->boot();

            try {
                $module->dispatchEvent($event);
            } catch (\Throwable $e) {
                dd($e);
            }
        }
    }

    private function isDispatchableModule(ModuleAdapterInterface $moduleAdapter): bool
    {
        return in_array($moduleAdapter->name(), $this->availableModules);
    }

    private function makeIntegrationEvent(Message $message): IntegrationEventMessage
    {
        $eventClass = $message->event();

        assert(is_subclass_of($eventClass, IntegrationEventInterface::class));

        return new IntegrationEventMessage(
            module: $message->module(),
            event: unserialize($message->payload()),
            context: $message->context()
        );
    }
}