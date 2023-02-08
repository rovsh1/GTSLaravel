<?php

namespace Custom\Framework\Contracts\Event;

interface DomainEventListenerInterface
{
    public function handle(DomainEventInterface $event);
}
