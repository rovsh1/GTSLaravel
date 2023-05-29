<?php

namespace Sdk\Module\Contracts\Bus;

use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\ModuleInterface;

interface IntegrationEventBusInterface
{
    public function publish(DomainEventInterface $event): void;

    public function subscribe(ModuleInterface $module, string $event, string $listener): void;
}
