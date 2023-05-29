<?php

namespace Sdk\Module\Bus;

use Sdk\Module\Contracts\Bus\IntegrationEventBusInterface;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\IntegrationEventInterface;
use Sdk\Module\Contracts\ModuleInterface;
use Sdk\Module\Event\IntegrationEvent;

final class IntegrationEventBus implements IntegrationEventBusInterface
{
    private array $subscribes = [];

    public function publish(DomainEventInterface $event): void
    {
        //@todo wrap to integration event and use queue
        $eventClass = get_class($event);
        foreach ($this->subscribes as $alias => $listeners) {
            if ($alias !== $eventClass) {
                continue;
            }

            foreach ($listeners as $params) {
                $params[0]->boot();
                $params[0]->make($params[1])->handle($event);
            }
        }
    }

    public function subscribe(ModuleInterface $module, string $event, string $listener): void
    {
        if (!isset($this->subscribes[$event])) {
            $this->subscribes[$event] = [];
        }
        $this->subscribes[$event][] = [$module, $listener];
    }

//    private function buildIntegrationEvent(DomainEventInterface $event): IntegrationEventInterface
//    {
//        return new IntegrationEvent($this->module->name(), $event);
//    }
}
