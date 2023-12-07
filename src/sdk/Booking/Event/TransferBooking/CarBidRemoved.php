<?php

namespace Sdk\Booking\Event\TransferBooking;

use Sdk\Booking\Contracts\Entity\DetailsInterface;
use Sdk\Booking\Contracts\Event\CarBidChangedInterface;
use Sdk\Booking\Entity\CarBid;
use Sdk\Booking\Support\Event\AbstractDetailsEvent;
use Sdk\Shared\Event\IntegrationEventMessages;

class CarBidRemoved extends AbstractDetailsEvent implements CarBidChangedInterface
{
    public function __construct(
        DetailsInterface $details,
        public readonly CarBid $carBid,
    ) {
        parent::__construct($details);
    }

    public function integrationEvent(): string
    {
        return IntegrationEventMessages::TRANSFER_BOOKING_CAR_BID_REMOVED;
    }

    public function integrationPayload(): array
    {
        return [
            'bookingId' => $this->bookingId()->value(),
            'guest' => $this->carBid->serialize()
        ];
    }
}
