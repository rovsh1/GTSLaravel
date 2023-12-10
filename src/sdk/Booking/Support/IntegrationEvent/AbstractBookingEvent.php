<?php

namespace Sdk\Booking\Support\IntegrationEvent;

use Sdk\Booking\IntegrationEvent\BookingEventInterface;
use Sdk\Module\Contracts\Event\IntegrationEventInterface;

abstract class AbstractBookingEvent implements IntegrationEventInterface, BookingEventInterface
{
    public function __construct(
        public readonly int $bookingId
    ) {}
}