<?php

namespace Module\Booking\Domain\HotelBooking\Listener;

use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;

class RequestableDocumentSentListener implements DomainEventListenerInterface
{
    public function handle(DomainEventInterface $event) {

    }
}