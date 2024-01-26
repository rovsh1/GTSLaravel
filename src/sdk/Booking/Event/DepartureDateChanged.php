<?php

declare(strict_types=1);

namespace Sdk\Booking\Event;

use Sdk\Booking\Contracts\Event\InvoiceBecomeDeprecatedEventInterface;
use Sdk\Booking\Support\Event\AbstractDetailsEvent;
use Sdk\Booking\ValueObject\BookingId;

class DepartureDateChanged extends AbstractDetailsEvent implements BookingDateChangedEventInterface,
                                                                   InvoiceBecomeDeprecatedEventInterface
{
    public function bookingId(): BookingId
    {
        return $this->details->bookingId();
    }
}
