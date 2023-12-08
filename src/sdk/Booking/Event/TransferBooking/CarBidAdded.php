<?php

namespace Sdk\Booking\Event\TransferBooking;

use Sdk\Booking\Contracts\Event\CarBidChangedInterface;
use Sdk\Booking\Entity\CarBid;
use Sdk\Shared\Event\IntegrationEventMessages;

class CarBidAdded extends AbstractCarBidEvent implements CarBidChangedInterface
{
    public function __construct(
        CarBid $carBid,
    ) {
        parent::__construct($carBid);
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
