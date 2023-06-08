<?php

namespace Module\Booking\Hotel\Domain\Event;

use Module\Booking\Common\Domain\Event\BookingEventInterface;
use Module\Booking\Common\Domain\Event\StatusBookingEventInterface;

class GuestNotCheckIn implements BookingEventInterface, StatusBookingEventInterface
{
    public function __construct(int $bookingId) {}
}
