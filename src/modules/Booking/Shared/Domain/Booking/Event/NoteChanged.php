<?php

namespace Module\Booking\Shared\Domain\Booking\Event;

use Module\Booking\Shared\Domain\Booking\Booking;
use Sdk\Booking\Support\Event\AbstractBookingEvent;
use Sdk\Shared\Event\IntegrationEventMessages;

final class NoteChanged extends AbstractBookingEvent
{
    public function __construct(
        Booking $booking,
        public readonly ?string $noteBefore,
    ) {
        parent::__construct($booking);
    }

    public function integrationEvent(): string
    {
        return IntegrationEventMessages::BOOKING_DETAILS_MODIFIED;
    }

    public function integrationPayload(): array
    {
        return [
            'bookingId' => $this->bookingId()->value(),
            '@attribute' => 'note',
            'before' => $this->noteBefore,
            'after' => $this->booking->note()
        ];
    }
}
