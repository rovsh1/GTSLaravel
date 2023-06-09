<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Event\Status;

use Module\Booking\Common\Domain\Event\AbstractBookingEvent;
use Module\Booking\Common\Domain\Event\BookingStatusEventInterface;

abstract class AbstractStatusEvent extends AbstractBookingEvent implements BookingStatusEventInterface
{

}
