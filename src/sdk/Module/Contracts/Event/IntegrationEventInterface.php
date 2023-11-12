<?php

namespace Sdk\Module\Contracts\Event;

interface IntegrationEventInterface
{
    public function integrationEvent(): string;

    public function integrationPayload(): array;
}
