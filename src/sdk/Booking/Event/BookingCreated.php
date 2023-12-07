<?php

namespace Sdk\Booking\Event;

use Sdk\Booking\IntegrationEvent\BookingCreated as IntegrationEvent;
use Sdk\Booking\Support\Event\AbstractBookingEvent;

final class BookingCreated extends AbstractBookingEvent
{
    public function integrationEvent(): IntegrationEvent
    {
        return new IntegrationEvent($this->bookingId()->value());
    }
}
