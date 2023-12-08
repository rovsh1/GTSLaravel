<?php

namespace Sdk\Booking\Event\TransferBooking;

use Sdk\Booking\Contracts\Event\CarBidChangedInterface;
use Sdk\Booking\Entity\CarBid;
use Sdk\Booking\ValueObject\CarBid\CarBidDetails;
use Sdk\Shared\Event\IntegrationEventMessages;

class CarBidDetailsEdited extends AbstractCarBidEvent implements CarBidChangedInterface
{
    public function __construct(
        CarBid $carBid,
        public readonly CarBidDetails $detailsBefore,
    ) {
        parent::__construct($carBid);
    }

    public function integrationEvent(): string
    {
        return IntegrationEventMessages::TRANSFER_BOOKING_CAR_BID_DETAILS_EDITED;
    }

    public function integrationPayload(): array
    {
        return [
            'bookingId' => $this->bookingId()->value(),
            'detailsBefore' => $this->detailsBefore->serialize(),
            'detailsAfter' => $this->carBid->details()->serialize()
        ];
    }
}
