<?php

namespace Module\Booking\HotelBooking\Domain\Event;

use Module\Booking\Common\Domain\Event\BookingEventInterface;
use Module\Booking\Common\Domain\Event\BookingStatusEventInterface;

class GuestNotCheckIn implements BookingEventInterface, BookingStatusEventInterface
{
    public function __construct(int $bookingId) {}
}
