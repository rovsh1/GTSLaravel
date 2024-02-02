<?php

namespace Sdk\Booking\Event\TransferBooking;

use Sdk\Booking\Contracts\Event\CarBidEventInterface;
use Sdk\Booking\Contracts\Event\InvoiceBecomeDeprecatedEventInterface;
use Sdk\Booking\Entity\CarBid;
use Sdk\Booking\ValueObject\CarBid\CarBidPrices;

class CarBidPricesChanged extends AbstractCarBidEvent implements
    CarBidEventInterface,
    InvoiceBecomeDeprecatedEventInterface
{
    public function __construct(
        CarBid $carBid,
        public readonly CarBidPrices $pricesBefore,
    ) {
        parent::__construct($carBid);
    }
}
