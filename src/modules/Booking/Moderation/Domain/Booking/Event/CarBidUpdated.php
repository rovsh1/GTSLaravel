<?php

namespace Module\Booking\Moderation\Domain\Booking\Event;

use Module\Booking\Shared\Domain\Booking\Booking;
use Sdk\Booking\Support\AbstractBookingEvent;
use Sdk\Booking\ValueObject\CarBid;
use Sdk\Shared\Event\IntegrationEventMessages;

class CarBidUpdated extends AbstractBookingEvent implements CarBidChangedInterface
{
    public function __construct(
        Booking $booking,
        public readonly CarBid $carBidBefore,
        public readonly CarBid $carBidAfter,
    ) {
        parent::__construct($booking);
    }

    public function integrationEvent(): string
    {
        return IntegrationEventMessages::TRANSFER_BOOKING_CAR_BID_UPDATED;
    }

    public function integrationPayload(): array
    {
        return [
            'bookingId' => $this->booking->id()->value(),
            'before' => $this->carBidBefore->serialize(),
            'after' => $this->carBidAfter->serialize()
        ];
    }
}
