<?php

namespace Module\Booking\HotelBooking\Domain\Listener;

use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;

class RequestableDocumentSentListener implements DomainEventListenerInterface
{
    public function handle(DomainEventInterface $event) {

    }
}