<?php

namespace Sdk\Booking\Event\TransferBooking;

use Sdk\Booking\Contracts\Entity\DetailsInterface;
use Sdk\Booking\Contracts\Event\CarBidChangedInterface;
use Sdk\Booking\Entity\CarBid;
use Sdk\Booking\Support\Event\AbstractDetailsEvent;
use Sdk\Shared\Event\IntegrationEventMessages;

class CarBidReplaced extends AbstractDetailsEvent implements CarBidChangedInterface
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
        return IntegrationEventMessages::TRANSFER_BOOKING_CAR_BID_REPLACED;
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
