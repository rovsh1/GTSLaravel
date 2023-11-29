<?php

namespace Module\Booking\Moderation\Domain\Booking\Event;

use Module\Booking\Shared\Domain\Booking\Booking;
use Sdk\Booking\Support\AbstractBookingEvent;
use Sdk\Booking\ValueObject\CarBid;
use Sdk\Shared\Event\IntegrationEventMessages;

class CarBidAdded extends AbstractBookingEvent implements CarBidChangedInterface
{
    public function __construct(
        Booking $booking,
        public readonly CarBid $carBid,
    ) {
        parent::__construct($booking);
    }

    public function integrationEvent(): string
    {
        return IntegrationEventMessages::TRANSFER_BOOKING_CAR_BID_ADDED;
    }

    public function integrationPayload(): array
    {
        return [
            'bookingId' => $this->booking->id()->value(),
            'carBid' => $this->carBid->serialize()
        ];
    }
}
