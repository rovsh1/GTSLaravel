<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Event\Status;

use Module\Booking\Shared\Domain\Booking\Event\AbstractBookingEvent;
use Sdk\Booking\Contracts\Event\BookingStatusEventInterface;
use Sdk\Shared\Event\IntegrationEventMessages;

abstract class AbstractStatusEvent extends AbstractBookingEvent implements BookingStatusEventInterface
{
    public function integrationEvent(): string
    {
        return IntegrationEventMessages::BOOKING_STATUS_UPDATED;
    }

    public function integrationPayload(): array
    {
        return [
            'bookingId' => $this->booking->id()->value(),
            'status' => $this->booking->status()->value,
            'statusBefore' => null
        ];
    }
}
