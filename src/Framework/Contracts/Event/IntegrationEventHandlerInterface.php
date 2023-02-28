<?php

namespace Custom\Framework\Contracts\Event;

interface IntegrationEventHandlerInterface
{
    public function handle(IntegrationEventInterface $event);
}
