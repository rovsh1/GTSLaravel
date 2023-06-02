<?php

namespace Sdk\Module\Event;

use Sdk\Module\Contracts\Event\DomainEventHandlerInterface;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\IntegrationEventHandlerInterface;
use Sdk\Module\Contracts\Event\IntegrationEventInterface;
use Sdk\Module\Foundation\Module;

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
