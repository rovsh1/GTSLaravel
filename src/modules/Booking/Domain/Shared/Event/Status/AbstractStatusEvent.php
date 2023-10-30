<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Shared\Event\Status;

use Module\Booking\Domain\Shared\Event\AbstractBookingEvent;
use Module\Booking\Domain\Shared\Event\BookingStatusEventInterface;

abstract class AbstractStatusEvent extends AbstractBookingEvent implements BookingStatusEventInterface
{

}
