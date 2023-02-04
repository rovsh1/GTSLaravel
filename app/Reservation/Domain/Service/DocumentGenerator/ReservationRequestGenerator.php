<?php

namespace GTS\Reservation\Domain\Service\DocumentGenerator;

use GTS\Reservation\Domain\Entity\Document\ReservationRequest;
use GTS\Reservation\Domain\Entity\ReservationRequestableInterface;

class ReservationRequestGenerator extends AbstractGenerator
{
    public function generate(ReservationRequestableInterface $document): ReservationRequest {}
}
