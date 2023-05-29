<?php

namespace Sdk\Module\Event;

use Sdk\Module\Contracts\Event\IntegrationEventHandlerInterface;
use Sdk\Module\Contracts\Event\IntegrationEventInterface;
use Sdk\Module\Foundation\Module;

class DomainEventHandler implements \Sdk\Module\Contracts\Event\DomainEventHandlerInterface
{
    public function __construct(
        private readonly Module $module,
        private readonly IntegrationEventHandlerInterface $integrationEventHandler
    ) {}

    public function handle(\Sdk\Module\Contracts\Event\DomainEventInterface $event)
    {
        $this->integrationEventHandler->handle($this->buildIntegrationEvent($event));
    }

    private function buildIntegrationEvent(\Sdk\Module\Contracts\Event\DomainEventInterface $event): IntegrationEventInterface
    {
        $eventsNamespace = $this->module->namespace('Domain\Event');

        return new IntegrationEvent(
            $this->module->name(),
            str_replace($eventsNamespace . '\\', '', get_class($event)),
            (array)$event
        );
    }
}
