<?php

namespace Sdk\Module\Contracts\Event;

final class IntegrationEventMessage
{
    public function __construct(
        public readonly string $module,
        public readonly string $event,
        public readonly array $payload,
        public readonly array $context,
    ) {
    }
}
