<?php

namespace Module\Booking\Domain\HotelBooking\Event;

use Module\Booking\Domain\Shared\Event\BookingEventInterface;
use Module\Booking\Domain\Shared\Event\BookingStatusEventInterface;

class GuestNotCheckIn implements BookingEventInterface, BookingStatusEventInterface
{
    public function __construct(int $bookingId) {}
}
