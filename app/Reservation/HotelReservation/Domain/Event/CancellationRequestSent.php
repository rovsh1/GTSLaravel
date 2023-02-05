<?php

namespace GTS\Reservation\HotelReservation\Domain\Event;

use GTS\Reservation\Common\Domain\Event\EventInterface;
use GTS\Reservation\Common\Domain\Event\RequestEventInterface;
use GTS\Reservation\Common\Domain\Event\StatusEventInterface;

class CancellationRequestSent implements EventInterface, StatusEventInterface, RequestEventInterface
{

}
