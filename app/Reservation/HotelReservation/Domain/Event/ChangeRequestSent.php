<?php

namespace GTS\Reservation\HotelReservation\Domain\Event;

use GTS\Reservation\Common\Domain\Event\EventInterface;
use GTS\Reservation\Common\Domain\Event\RequestEventInterface;
use GTS\Reservation\Common\Domain\Event\StatusEventInterface;

class ChangeRequestSent implements EventInterface, StatusEventInterface, RequestEventInterface
{

}
