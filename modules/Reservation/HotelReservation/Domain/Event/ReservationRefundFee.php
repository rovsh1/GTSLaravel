<?php

namespace Module\Reservation\HotelReservation\Domain\Event;

use Module\Reservation\Common\Domain\Event\EventInterface;
use Module\Reservation\Common\Domain\Event\StatusEventInterface;

class ReservationRefundFee implements EventInterface, StatusEventInterface
{

}
