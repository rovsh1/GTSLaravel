<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Shared\Event\Status;

use Module\Booking\Shared\Domain\Shared\Event\AbstractBookingEvent;
use Module\Booking\Shared\Domain\Shared\Event\BookingStatusEventInterface;

abstract class AbstractStatusEvent extends AbstractBookingEvent implements BookingStatusEventInterface
{

}
