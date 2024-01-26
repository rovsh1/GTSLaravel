<?php

namespace Sdk\Booking\Event\TransferBooking;

use Sdk\Booking\Contracts\Event\CarBidEventInterface;
use Sdk\Booking\Contracts\Event\InvoiceBecomeDeprecatedEventInterface;
use Sdk\Booking\Entity\CarBid;
use Sdk\Booking\ValueObject\CarBid\CarBidDetails;

class CarBidDetailsEdited extends AbstractCarBidEvent implements
    CarBidEventInterface,
    InvoiceBecomeDeprecatedEventInterface
{
    public function __construct(
        CarBid $carBid,
        public readonly CarBidDetails $detailsBefore,
    ) {
        parent::__construct($carBid);
    }
}
