<?php

namespace GTS\Reservation\Domain\Service\DocumentGenerator\HotelReservation;

use GTS\Reservation\Domain\Entity\Document\CancellationRequest;
use GTS\Reservation\Domain\Entity\ReservationRequestableInterface;
use GTS\Reservation\Domain\Service\DocumentGenerator\AbstractGenerator;

class CancellationRequestGenerator extends AbstractGenerator
{
    public function generate(ReservationRequestableInterface $reservation): CancellationRequest {}
}
