<?php

namespace Custom\Framework\Event;

use Custom\Framework\Contracts\Event\DomainEventHandlerInterface;
use Custom\Framework\Contracts\Event\DomainEventInterface;
use Custom\Framework\Contracts\Event\IntegrationEventHandlerInterface;
use Custom\Framework\Contracts\Event\IntegrationEventInterface;
use Custom\Framework\Foundation\Module;

class DomainEventHandler implements DomainEventHandlerInterface
{
    public function __construct(
        private readonly Module $module,
        private readonly IntegrationEventHandlerInterface $integrationEventHandler
    ) {}

    public function handle(DomainEventInterface $event)
    {
        $this->integrationEventHandler->handle($this->buildIntegrationEvent($event));
    }

    private function buildIntegrationEvent(DomainEventInterface $event): IntegrationEventInterface
    {
        $eventsNamespace = $this->module->namespace('Domain\Event');

        return new IntegrationEvent(
            $this->module->name(),
            str_replace($eventsNamespace . '\\', '', get_class($event)),
            (array)$event
        );
    }
}
