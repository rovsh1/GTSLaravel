<?php

namespace Sdk\Booking\Contracts\Event;

use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\Event\DomainEventInterface;

interface BookingEventInterface extends DomainEventInterface
{
    public function bookingId(): BookingId;
}
