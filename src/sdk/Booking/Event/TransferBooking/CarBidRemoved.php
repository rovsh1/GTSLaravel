<?php

namespace Sdk\Booking\Event\TransferBooking;

use Sdk\Booking\Contracts\Event\CarBidEventInterface;
use Sdk\Booking\Contracts\Event\InvoiceBecomeDeprecatedEventInterface;

class CarBidRemoved extends AbstractCarBidEvent implements
    CarBidEventInterface,
    InvoiceBecomeDeprecatedEventInterface
{
}
