<?php

namespace Sdk\Booking\Event\TransferBooking;

use Sdk\Booking\Contracts\Event\CarBidCancelConditionsDeprecatedEventInterface;
use Sdk\Booking\Contracts\Event\CarBidEventInterface;
use Sdk\Booking\Contracts\Event\InvoiceBecomeDeprecatedEventInterface;
use Sdk\Booking\Entity\CarBid;
use Sdk\Booking\ValueObject\BookingId;

class CarBidReplaced implements
    CarBidEventInterface,
    CarBidCancelConditionsDeprecatedEventInterface,
    InvoiceBecomeDeprecatedEventInterface
{
    public function __construct(
        public readonly CarBid $carBidBefore,
        public readonly CarBid $carBidAfter,
    ) {}

    public function bookingId(): BookingId
    {
        return $this->carBidBefore->bookingId();
    }
}
