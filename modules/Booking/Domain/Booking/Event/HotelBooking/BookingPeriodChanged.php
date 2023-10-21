<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Booking\Event\HotelBooking;

use Module\Booking\Domain\Booking\Event\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Domain\Shared\Event\AbstractBookingEvent;

class BookingPeriodChanged extends AbstractBookingEvent implements PriceBecomeDeprecatedEventInterface
{

}
