<?php

namespace Sdk\Module\Contracts\Event;

use Module\Booking\Common\Domain\Event\BookingEventInterface;

interface DomainEventListenerInterface
{
    public function handle(BookingEventInterface $event);
}
