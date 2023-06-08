<?php

namespace Module\Booking\Hotel\Domain\Event;

use Module\Booking\Common\Domain\Event\BookingEventInterface;
use Module\Booking\Common\Domain\Event\StatusBookingEventInterface;

class ChangeRequestedFromGuest implements BookingEventInterface, StatusBookingEventInterface
{
    public function __construct(int $reservationId) {}
}
