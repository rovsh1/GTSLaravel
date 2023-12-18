<?php

declare(strict_types=1);

namespace Sdk\Booking\Event\HotelBooking;

use Sdk\Booking\Contracts\Entity\DetailsInterface;
use Sdk\Booking\Contracts\Event\PriceBecomeDeprecatedEventInterface;
use Sdk\Booking\Event\BookingDateChangedEventInterface;
use Sdk\Booking\Support\Event\AbstractDetailsEvent;
use Sdk\Booking\ValueObject\HotelBooking\BookingPeriod;

//@todo integration event
class BookingPeriodChanged extends AbstractDetailsEvent implements PriceBecomeDeprecatedEventInterface, BookingDateChangedEventInterface
{
    public function __construct(
        DetailsInterface $details,
        private readonly BookingPeriod $bookingPeriodBefore,
    ) {
        parent::__construct($details);
    }
}
