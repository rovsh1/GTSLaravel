<?php

declare(strict_types=1);

namespace Module\Booking\Deprecated\HotelBooking\Event;

use Module\Booking\Domain\Shared\Event\AbstractBookingEvent;

class BookingPeriodChanged extends AbstractBookingEvent implements PriceBecomeDeprecatedEventInterface
{

}
