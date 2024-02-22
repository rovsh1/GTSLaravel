<?php

declare(strict_types=1);

namespace Sdk\Booking\Event;

use Sdk\Booking\ValueObject\BookingId;

interface BookingDateChangedEventInterface
{
    public function bookingId(): BookingId;
}
