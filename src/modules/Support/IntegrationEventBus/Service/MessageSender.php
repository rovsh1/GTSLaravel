<?php

namespace Module\Support\IntegrationEventBus\Service;

use App\Shared\Contracts\Module\ModuleAdapterInterface;
use Module\Support\IntegrationEventBus\Entity\Message;
use Sdk\Module\Contracts\Event\IntegrationEventMessage;

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
        return new IntegrationEventMessage(
            module: $message->module(),
            event: $message->event(),
            payload: $message->payload(),
            context: $message->context()
        );
    }
}