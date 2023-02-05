<?php

namespace GTS\Reservation\Domain\Service\DocumentGenerator\HotelReservation;

use GTS\Reservation\Domain\Entity\Document\ReservationRequest;
use GTS\Reservation\Domain\Entity\ReservationRequestableInterface;
use GTS\Reservation\Domain\Service\DocumentGenerator\AbstractGenerator;

class ReservationRequestGenerator extends AbstractGenerator
{
    public function generate(ReservationRequestableInterface $document): ReservationRequest {}
}
