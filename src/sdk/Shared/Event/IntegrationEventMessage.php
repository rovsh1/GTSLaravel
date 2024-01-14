<?php

namespace Sdk\Shared\Event;

use Sdk\Shared\Contracts\Event\IntegrationEventInterface;

final class IntegrationEventMessage
{
    public function __construct(
        public readonly string $originator,
        public readonly IntegrationEventInterface $event,
        public readonly array $context,
    ) {}
}
