<?php

namespace Sdk\Booking\Event\TransferBooking;

use Sdk\Booking\Contracts\Event\CarBidChangedInterface;
use Sdk\Booking\Entity\CarBid;
use Sdk\Shared\Event\IntegrationEventMessages;

class CarBidReplaced extends AbstractCarBidEvent implements CarBidChangedInterface
{
    public function __construct(
        CarBid $carBid,
        public readonly CarBid $carBidBefore,
    ) {
        parent::__construct($carBid);
    }

    public function integrationEvent(): string
    {
        return IntegrationEventMessages::TRANSFER_BOOKING_CAR_BID_REPLACED;
    }

    public function integrationPayload(): array
    {
        return [
            'bookingId' => $this->bookingId()->value(),
            'before' => $this->carBidBefore->serialize(),
            'after' => $this->carBid->serialize()
        ];
    }
}
