<?php

namespace Module\Booking\Shared\Domain\Booking\Event;

use Sdk\Booking\Support\Event\AbstractBookingEvent;
use Sdk\Shared\Event\IntegrationEventMessages;

final class BookingDeleted extends AbstractBookingEvent
{
    public function integrationEvent(): string
    {
        return IntegrationEventMessages::BOOKING_DELETED;
    }

    public function integrationPayload(): array
    {
        return [
            'bookingId' => $this->bookingId()->value()
        ];
    }
}
