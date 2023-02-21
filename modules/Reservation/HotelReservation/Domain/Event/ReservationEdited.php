<?php

namespace Module\Reservation\HotelReservation\Domain\Event;

use Module\Reservation\Common\Domain\Event\EditEventInterface;
use Module\Reservation\Common\Domain\Event\EventInterface;

class ReservationEdited implements EventInterface, EditEventInterface
{

}
