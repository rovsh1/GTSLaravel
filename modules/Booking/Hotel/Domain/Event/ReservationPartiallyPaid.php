<?php

namespace Module\Booking\Hotel\Domain\Event;

use Module\Booking\Common\Domain\Event\EventInterface;
use Module\Booking\Common\Domain\Event\StatusEventInterface;

class ReservationPartiallyPaid implements EventInterface, StatusEventInterface
{

}
