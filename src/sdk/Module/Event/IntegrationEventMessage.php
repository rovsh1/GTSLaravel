<?php

namespace Sdk\Module\Event;

use Sdk\Module\Contracts\Event\IntegrationEventInterface;

final class IntegrationEventMessage
{
    public function __construct(
        public readonly string $module,
        public readonly IntegrationEventInterface $event,
        public readonly array $context,
    ) {}
}
