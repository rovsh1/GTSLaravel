<?php

namespace Module\Booking\Common\Domain\Event\Status;

use Module\Booking\Common\Domain\Entity\AbstractBooking;
use Module\Booking\Common\Domain\Event\BookingStatusEventInterface;

class BookingConfirmed extends AbstractStatusEvent {
}
