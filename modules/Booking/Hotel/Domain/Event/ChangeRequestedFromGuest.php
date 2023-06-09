<?php

namespace Module\Booking\Hotel\Domain\Event;

use Module\Booking\Common\Domain\Event\BookingEventInterface;
use Module\Booking\Common\Domain\Event\BookingStatusEventInterface;

class ChangeRequestedFromGuest implements BookingEventInterface, BookingStatusEventInterface
{
    public function __construct(int $reservationId) {}
}
