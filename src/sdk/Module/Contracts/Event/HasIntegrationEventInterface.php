<?php

namespace Sdk\Module\Contracts\Event;

use Sdk\Shared\Contracts\Event\IntegrationEventInterface;

interface HasIntegrationEventInterface
{
    public function integrationEvent(): IntegrationEventInterface;
}
