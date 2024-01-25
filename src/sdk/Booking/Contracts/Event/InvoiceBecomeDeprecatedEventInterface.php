<?php

declare(strict_types=1);

namespace Sdk\Booking\Contracts\Event;

use Sdk\Booking\ValueObject\BookingId;

interface InvoiceBecomeDeprecatedEventInterface
{
    public function bookingId(): BookingId;
}
