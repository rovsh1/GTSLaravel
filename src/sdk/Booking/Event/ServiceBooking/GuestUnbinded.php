<?php

namespace Sdk\Booking\Event\ServiceBooking;

use Sdk\Booking\Contracts\Entity\DetailsInterface;
use Sdk\Booking\Contracts\Event\PriceBecomeDeprecatedEventInterface;
use Sdk\Booking\Support\Event\AbstractDetailsEvent;
use Sdk\Booking\ValueObject\GuestId;

class GuestUnbinded extends AbstractDetailsEvent implements PriceBecomeDeprecatedEventInterface
{
    public function __construct(
        DetailsInterface $details,
        public readonly GuestId $guestId,
    ) {
        parent::__construct($details);
    }
}
