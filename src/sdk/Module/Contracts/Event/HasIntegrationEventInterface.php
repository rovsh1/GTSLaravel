<?php

namespace Sdk\Module\Contracts\Event;

interface HasIntegrationEventInterface
{
    public function integrationEvent(): IntegrationEventInterface;
}
