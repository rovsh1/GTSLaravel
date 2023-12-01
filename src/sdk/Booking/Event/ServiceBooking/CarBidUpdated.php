<?php

namespace Sdk\Booking\Event\ServiceBooking;

use Sdk\Booking\Contracts\Entity\DetailsInterface;
use Sdk\Booking\Contracts\Event\CarBidChangedInterface;
use Sdk\Booking\Support\Event\AbstractDetailsEvent;
use Sdk\Booking\ValueObject\CarBid;
use Sdk\Shared\Event\IntegrationEventMessages;

class CarBidUpdated extends AbstractDetailsEvent implements CarBidChangedInterface
{
    public function __construct(
        DetailsInterface $details,
        public readonly CarBid $carBidBefore,
        public readonly CarBid $carBidAfter,
    ) {
        parent::__construct($details);
    }

    public function integrationEvent(): string
    {
        return IntegrationEventMessages::TRANSFER_BOOKING_CAR_BID_UPDATED;
    }

    public function integrationPayload(): array
    {
        return [
            'bookingId' => $this->bookingId()->value(),
            'before' => $this->carBidBefore->serialize(),
            'after' => $this->carBidAfter->serialize()
        ];
    }
}
