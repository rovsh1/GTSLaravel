<?php

namespace GTS\Reservation\HotelReservation\Domain\Event;

use GTS\Reservation\Common\Domain\Event\EventInterface;
use GTS\Reservation\Common\Domain\Event\StatusEventInterface;

class ReservationNotConfirmed implements EventInterface, StatusEventInterface
{

}
