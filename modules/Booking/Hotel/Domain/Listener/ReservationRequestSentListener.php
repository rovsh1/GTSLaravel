<?php

namespace Module\Booking\Hotel\Domain\Listener;

use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;

class ReservationRequestSentListener implements DomainEventListenerInterface
{
    public function handle(DomainEventInterface $event) {}
}
