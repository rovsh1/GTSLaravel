<?php

namespace Sdk\Booking\Support\IntegrationEvent;

use Sdk\Booking\IntegrationEvent\BookingEventInterface;

abstract class AbstractBookingEvent implements BookingEventInterface
{
    public function __construct(
        public readonly int $bookingId
    ) {}
}