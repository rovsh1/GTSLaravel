<?php

namespace Custom\Framework\Contracts\Event;

interface IntegrationEventListenerInterface
{
    public function handle(IntegrationEventInterface $event);
}
