<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Domain\Booking\Event\HotelBooking;

use Module\Booking\Shared\Domain\Booking\Event\AbstractBookingEvent;
use Module\Booking\Shared\Domain\Booking\Event\PriceBecomeDeprecatedEventInterface;

class BookingPeriodChanged extends AbstractBookingEvent implements PriceBecomeDeprecatedEventInterface
{

}
