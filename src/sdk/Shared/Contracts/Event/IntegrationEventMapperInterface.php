<?php

namespace Sdk\Shared\Contracts\Event;

use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\IntegrationEventInterface;

interface IntegrationEventMapperInterface
{
    public function map(DomainEventInterface $event): IntegrationEventInterface;
}