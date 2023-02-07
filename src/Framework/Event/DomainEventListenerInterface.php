<?php

namespace Custom\Framework\Event;

interface DomainEventListenerInterface
{
    public function handle(DomainEventInterface $event);
}
