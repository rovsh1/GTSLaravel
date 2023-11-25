<?php

namespace Module\Booking\Shared\Domain\Booking\Event;

use Module\Booking\Shared\Domain\Booking\Booking;
use Sdk\Module\Contracts\Event\IntegrationEventInterface;
use Sdk\Shared\Event\IntegrationEventMessages;

final class BookingModified implements IntegrationEventInterface
{
    public function __construct(
        public readonly Booking $booking,
        public readonly array $dataBefore,
    ) {
    }

    public function integrationEvent(): string
    {
        return IntegrationEventMessages::BOOKING_MODIFIED;
    }

    public function integrationPayload(): array
    {
        return [
            'bookingId' => $this->booking->id()->value(),
            'dataBefore' => $this->dataBefore,
            'dataAfter' => $this->booking->serialize()
        ];
    }
}