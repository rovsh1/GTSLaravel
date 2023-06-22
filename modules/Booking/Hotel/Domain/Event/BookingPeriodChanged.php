<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\Event;

use Module\Booking\Common\Domain\Event\AbstractBookingEvent;
use Module\Booking\Common\Domain\Event\CalculationChangesEventInterface;
use Module\Booking\Common\Domain\Event\EditEventInterface;

class BookingPeriodChanged extends AbstractBookingEvent implements EditEventInterface, CalculationChangesEventInterface
{

}
