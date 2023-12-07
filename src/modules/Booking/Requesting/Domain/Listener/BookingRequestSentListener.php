<?php

namespace Module\Booking\Requesting\Domain\Listener;

use Module\Booking\Requesting\Domain\Event\BookingRequestSent;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;

class BookingRequestSentListener implements DomainEventListenerInterface
{
    public function __construct()
    {
    }

    public function handle(DomainEventInterface $event): void
    {
        assert($event instanceof BookingRequestSent);

        $this->sendMailNotifications($event);
    }

    private function sendMailNotifications(BookingRequestSent $event): void
    {
    }
}
