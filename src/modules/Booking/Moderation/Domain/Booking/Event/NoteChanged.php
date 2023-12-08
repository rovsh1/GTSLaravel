<?php

namespace Module\Booking\Moderation\Domain\Booking\Event;

use Module\Booking\Shared\Domain\Booking\Booking;
use Sdk\Booking\IntegrationEvent\NoteModified as IntegrationEvent;
use Sdk\Booking\Support\Event\AbstractBookingEvent;
use Sdk\Module\Contracts\Event\HasIntegrationEventInterface;

final class NoteChanged extends AbstractBookingEvent implements HasIntegrationEventInterface
{
    public function __construct(
        Booking $booking,
        public readonly ?string $noteBefore,
    ) {
        parent::__construct($booking);
    }

    public function integrationEvent(): IntegrationEvent
    {
        return new IntegrationEvent(
            $this->bookingId()->value(),
            $this->booking->note(),
            $this->noteBefore
        );
    }
}
