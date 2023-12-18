<?php

declare(strict_types=1);

namespace Sdk\Booking\Event;

use Sdk\Booking\Support\Event\AbstractDetailsEvent;
use Sdk\Booking\ValueObject\BookingId;

class BookingDateChanged extends AbstractDetailsEvent implements BookingDateChangedEventInterface
{
    public function bookingId(): BookingId
    {
        return $this->details->bookingId();
    }
}
