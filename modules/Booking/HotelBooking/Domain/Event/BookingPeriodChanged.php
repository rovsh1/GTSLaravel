<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\Event;

use Module\Booking\Common\Domain\Event\AbstractBookingEvent;

class BookingPeriodChanged extends AbstractBookingEvent implements PriceBecomeDeprecatedEventInterface
{

}
