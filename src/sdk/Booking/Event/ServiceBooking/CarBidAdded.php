<?php

namespace Sdk\Booking\Event\ServiceBooking;

use Sdk\Booking\Contracts\Entity\DetailsInterface;
use Sdk\Booking\Contracts\Event\CarBidChangedInterface;
use Sdk\Booking\Support\Event\AbstractDetailsEvent;
use Sdk\Booking\ValueObject\CarBid;
use Sdk\Shared\Event\IntegrationEventMessages;

class CarBidAdded extends AbstractDetailsEvent implements CarBidChangedInterface
{
    public function __construct(
        DetailsInterface $details,
        public readonly CarBid $carBid,
    ) {
        parent::__construct($details);
    }

    public function integrationEvent(): string
    {
        return IntegrationEventMessages::TRANSFER_BOOKING_CAR_BID_ADDED;
    }

    public function integrationPayload(): array
    {
        return [
            'bookingId' => $this->bookingId()->value(),
            'carBid' => $this->carBid->serialize()
        ];
    }
}
