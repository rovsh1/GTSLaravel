<?php

namespace Sdk\Booking\Event;

use Sdk\Booking\IntegrationEvent\BookingDeleted as IntegrationEvent;
use Sdk\Booking\Support\Event\AbstractBookingEvent;
use Sdk\Module\Contracts\Event\HasIntegrationEventInterface;

final class BookingDeleted extends AbstractBookingEvent implements HasIntegrationEventInterface
{
    public function integrationEvent(): IntegrationEvent
    {
        return new IntegrationEvent($this->bookingId()->value());
    }
}
