<?php

namespace Custom\Framework\Contracts\Event;

interface DomainEventHandlerInterface
{
    public function handle(DomainEventInterface $event);
}
